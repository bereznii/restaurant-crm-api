<?php

namespace App\Repositories;

use App\Models\Order\Order;

class OrderRepository extends AbstractRepository
{
    private const DEFAULT_PAGE_SIZE = 20;

    /** @var string */
    protected string $modelClass = Order::class;

    /**
     * @param array $queryParams
     * @return mixed
     */
    public function index(array $queryParams): mixed
    {
        return $this->_getInstance()
            ->with([
                'address',
                'items',
                'client',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(
                (int) ($queryParams['per_page'] ?? self::DEFAULT_PAGE_SIZE)
            );
    }
}
