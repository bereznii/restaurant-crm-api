<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * @param Product $product
     * @param array $attributes
     * @return Product
     */
    public function update(Product $product, array $attributes): Product
    {
        $product->title_ua = $attributes['title_ua'] ?? $product->title_ua;
        $product->title_ru = $attributes['title_ru'] ?? $product->title_ru;
        $product->is_active = (int) ($attributes['is_active'] ?? $product->is_active);
        $product->type_sync_id = $attributes['type_sync_id'] ?? $product->type_sync_id;
        $product->description_ua = $attributes['description_ua'] ?? $product->description_ua;
        $product->description_ru = $attributes['description_ru'] ?? $product->description_ru;
        $product->save();

        if (isset($attributes['prices']) && is_array($attributes['prices'])) {
            foreach ($attributes['prices'] as $priceToSave) {
                ProductPrice::where([
                    ['city_sync_id', $priceToSave['city_sync_id']],
                    ['article', $product->article]
                ])->update([
                    'price_old' => $priceToSave['price_old']
                ]);
            }
        }

        return Product::where('id', $product->id)->with('prices:city_sync_id,article,price,price_old', 'type')->first();
    }

    /**
     * @param string $restaurant
     * @param array $receivedProducts
     * @return bool
     */
    public function massStore(string $restaurant, array $receivedProducts): bool
    {
        $existingProducts = Product::where('restaurant', '=', $restaurant)->get();

        foreach ($receivedProducts as $receivedProduct) {
            $correspondentExistingProduct = $existingProducts->firstWhere('article', $receivedProduct['article']);

            if (isset($correspondentExistingProduct)) {
                $this->updateExistingProduct($receivedProduct, $correspondentExistingProduct);
            } else {
                $this->createNewProduct($receivedProduct, $restaurant);
            }
        }

        return true;
    }

    /**
     * @param array $receivedProduct
     * @param Product $correspondentExistingProduct
     */
    private function updateExistingProduct(array $receivedProduct, Product $correspondentExistingProduct): void
    {
        $correspondentExistingProduct->weight = $receivedProduct['weight'];
        $correspondentExistingProduct->weight_type = $receivedProduct['weight_type'];
        $correspondentExistingProduct->save();

        if (isset($receivedProduct['prices']) && is_array($receivedProduct['prices'])) {
            foreach ($receivedProduct['prices'] as $receivedProductPrice) {
                ProductPrice::where([
                    ['article', $receivedProduct['article']],
                    ['city_sync_id', $receivedProductPrice['city']],
                ])->update([
                    'price' => $receivedProductPrice['price']
                ]);
            }
        }
    }

    /**
     * @param array $receivedProduct
     * @param string $restaurant
     */
    private function createNewProduct(array $receivedProduct, string $restaurant)
    {
        $product = new Product();
        $product->restaurant = $restaurant;
        $product->title_ua = $receivedProduct['title_ua'];
        $product->title_ru = $receivedProduct['title_ua'];
        $product->article = $receivedProduct['article'];
        $product->is_active = 0;
        $product->weight = $receivedProduct['weight'];
        $product->weight_type = $receivedProduct['weight_type'];
        $product->save();

        if (isset($receivedProduct['prices']) && is_array($receivedProduct['prices'])) {
            $date = date('Y-m-d H:i:s');
            foreach ($receivedProduct['prices'] as $receivedProductPrice) {
                DB::table('product_prices')->insert([
                    'city_sync_id' => $receivedProductPrice['city'],
                    'article' => $receivedProduct['article'],
                    'price' => $receivedProductPrice['price'],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
