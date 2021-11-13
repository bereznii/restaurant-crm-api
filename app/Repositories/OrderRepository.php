<?php

namespace App\Repositories;

use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;

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
        $cookKitchen = Auth::user()->kitchen_code;

        $res = $this->_getInstance()
            ->with([
                'address',
                'items',
                'client',
                'history'
            ])
            ->where('kitchen_code', '=', $cookKitchen)
            ->orderByRaw(
                "CASE
                WHEN orders.status = 'new' THEN 1
                WHEN orders.status = 'cooking' THEN 2
                WHEN orders.status = 'preparing' THEN 3
                WHEN orders.status = 'for_delivery' THEN 4
                WHEN orders.status = 'closed' THEN 5
                WHEN orders.status = 'rejected' THEN 6
                 END asc"
            )
            ->orderBy('created_at', 'desc')
            ->paginate(
                (int) ($queryParams['per_page'] ?? self::DEFAULT_PAGE_SIZE)
            );

        return $res;
    }
}
