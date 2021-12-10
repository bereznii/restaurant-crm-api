<?php

namespace App\Services;

use App\Models\City;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /** @var array  */
    private array $citiesIndexedByUuids = [];

    /**
     *
     */
    public function __construct()
    {
        $this->citiesIndexedByUuids = City::all()->pluck('sync_id', 'uuid')->toArray();
    }

    /**
     * @param Product $product
     * @param array $attributes
     * @return Product
     */
    public function update(Product $product, array $attributes): Product
    {
        DB::beginTransaction();

        try {
            $product->title_ua = $attributes['title_ua'];
            $product->title_ru = $attributes['title_ru'];
            $product->type_sync_id = $attributes['type_sync_id'];
            $product->category_sync_id = $attributes['category_sync_id'];
            $product->description_ua = $attributes['description_ua'];
            $product->description_ru = $attributes['description_ru'];
            $product->save();

            if (isset($attributes['prices']) && is_array($attributes['prices'])) {
                foreach ($attributes['prices'] as $priceToSave) {
                    ProductPrice::where([
                        ['city_sync_id', '=', $priceToSave['city_sync_id']],
                        ['product_id', '=', $product->id]
                    ])->update([
                        'price_old' => $priceToSave['price_old'],
                        'is_active' => $priceToSave['is_active'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(Auth::id() . ' | ' . $e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }

        return Product::where('id', $product->id)
            ->with('prices:product_id,city_sync_id,price,price_old,is_active','type:sync_id,name','category:sync_id,name')
            ->first();
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
            $correspondentExistingProduct = $existingProducts->firstWhere('id', $receivedProduct['product_uuid']);

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
                    ['product_id', '=', $correspondentExistingProduct->id],
                    ['city_sync_id', '=', $this->citiesIndexedByUuids[$receivedProductPrice['city_uuid']]],
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
        DB::beginTransaction();

        try {
            $product = new Product();
            $product->id = $receivedProduct['product_uuid'];
            $product->restaurant = $restaurant;
            $product->title_ua = $receivedProduct['title_ua'];
            $product->title_ru = $receivedProduct['title_ua'];
            $product->article = $receivedProduct['article'];
            $product->weight = $receivedProduct['weight'];
            $product->weight_type = $receivedProduct['weight_type'];
            $product->save();

            if (isset($receivedProduct['prices']) && is_array($receivedProduct['prices'])) {
                $date = date('Y-m-d H:i:s');
                foreach ($receivedProduct['prices'] as $receivedProductPrice) {
                    DB::table('product_prices')->insert([
                        'product_id' => $product->id,
                        'city_sync_id' => $this->citiesIndexedByUuids[$receivedProductPrice['city_uuid']],
                        'price' => $receivedProductPrice['price'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(Auth::id() . ' | ' . $e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }
    }
}
