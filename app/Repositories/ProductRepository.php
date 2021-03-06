<?php

namespace App\Repositories;

use App\Models\Product\Product;

class ProductRepository extends AbstractRepository
{
    private const DEFAULT_PAGE_SIZE = 20;

    /** @var string */
    protected string $modelClass = Product::class;

    /**
     * @param string $id
     * @return Product
     */
    public function show(string $id): Product
    {
        /** @var Product $product */
        $product = $this->_getInstance()
            ->with('prices:product_id,city_sync_id,price,price_old,is_active', 'type:sync_id,name', 'category:sync_id,name')
            ->findOrFail($id);

        return $product;
    }

    /**
     * @param array $queryParams
     * @return mixed
     */
    public function index(array $queryParams): mixed
    {
        $query = $this->_getInstance()
            ->with([
//                'prices' => function ($query) use ($queryParams) {
//                    $query->filterWhere('city_sync_id', '=', $queryParams['city_sync_id'] ?? null)
//                        ->select(['product_id', 'city_sync_id', 'price', 'price_old']);
//                },
                'prices:product_id,city_sync_id,price,price_old,is_active',
                'type:sync_id,name',
                'category:sync_id,name',
            ])
            ->filterWhere('restaurant', '=', $queryParams['restaurant'] ?? null)
            ->filterWhere('type_sync_id', '=', $queryParams['type_sync_id'] ?? null)
            ->filterWhere('category_sync_id', '=', $queryParams['category_sync_id'] ?? null);

        if (!empty($queryParams['search'])) {
            $query->where('title_ua', 'like', "%{$queryParams['search']}%")
                ->orWhere('title_ru', 'like', "%{$queryParams['search']}%")
                ->orWhere('article', 'like', "%{$queryParams['search']}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate(
            (int) ($queryParams['per_page'] ?? self::DEFAULT_PAGE_SIZE)
        );
    }
}
