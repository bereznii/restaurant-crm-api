<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    private const DEFAULT_PAGE_SIZE = 50;

    /** @var string */
    protected string $modelClass = Product::class;

    /**
     * @param int $id
     * @return Product
     */
    public function show(int $id): Product
    {
        return $this->_getInstance()
            ->with('prices:product_id,city_sync_id,price,price_old', 'type:sync_id,name')
            ->findOrFail($id);
    }

    /**
     * @param array $queryParams
     * @return mixed
     */
    public function index(array $queryParams): mixed
    {
        return $this->_getInstance()
            ->with([
                'prices' => function ($query) use ($queryParams) {
                    $query->filterWhere('city_sync_id', '=', $queryParams['city_sync_id'] ?? null)
                        ->select(['product_id', 'city_sync_id', 'price', 'price_old']);
                },
                'type:sync_id,name'
            ])
            ->filterWhere('restaurant', '=', $queryParams['restaurant'] ?? null)
            ->filterWhere('type_sync_id', '=', $queryParams['type_sync_id'] ?? null)
            ->orderBy('created_at', 'desc')
            ->paginate(
                (int) ($queryParams['per_page'] ?? self::DEFAULT_PAGE_SIZE)
            );
    }

    /**
     * @param array $queryParams
     */
    public function search(array $queryParams)
    {
        dd($queryParams);
    }
}