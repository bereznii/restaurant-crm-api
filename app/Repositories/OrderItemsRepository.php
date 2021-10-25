<?php

namespace App\Repositories;

use App\Models\Order\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderItemsRepository extends AbstractRepository
{
    private const DEFAULT_PAGE_SIZE = 20;

    /** @var string */
    protected string $modelClass = OrderItem::class;

    /**
     * @param array $queryParams
     * @return mixed
     */
    public function index(array $queryParams): mixed
    {
        $cookKitchen = Auth::user()->kitchen_code;
        $cookProductTypes = Auth::user()->productTypes->pluck('product_type_sync_id')->toArray();

        return $this->_getInstance()
            ->with('product')
            ->whereHas('order', function ($query) use ($cookKitchen) {
                return $query->where('kitchen_code', '=', $cookKitchen);
            })
            ->whereHas('product', function ($query) use ($cookProductTypes) {
                return $query->whereIn('type_sync_id', $cookProductTypes);
            })
            ->orderBy('order_items.created_at', 'desc')
            ->paginate(
                (int) ($queryParams['per_page'] ?? self::DEFAULT_PAGE_SIZE)
            );
    }
}
