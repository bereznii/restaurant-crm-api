<?php

namespace App\Services;

use App\Models\City;
use App\Models\Client\Client;
use App\Models\Order\Order;
use App\Models\Order\OrderAddress;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderItemsService
{
    /**
     * @param array $validated
     * @param OrderItem $orderItem
     * @param int $userId
     * @return OrderItem|null
     */
    public function update(array $validated, OrderItem $orderItem, int $userId): ?OrderItem
    {
        DB::beginTransaction();

        try {
            $orderItem->cook_id = $userId;
            $orderItem->status = $validated['status'];
            $orderItem->save();

            $this->moveOrderToPreparing($orderItem);

            DB::commit();

            $orderItem =  OrderItem::where('id', $orderItem->id)
                ->with('product', 'order')
                ->first();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(Auth::id() . ' | ' . $e->getMessage());
        }

        return $orderItem ?? null;
    }

    /**
     * @param OrderItem $orderItem
     */
    private function moveOrderToPreparing(OrderItem $orderItem)
    {
        $nonReadyItemsInCurrentOrder = OrderItem::where([
            ['order_id', '=', $orderItem->order_id],
            ['status', '!=', OrderItem::STATUS_READY],
        ])->exists();

        if (!$nonReadyItemsInCurrentOrder) {
            $order = Order::find($orderItem->order_id);
            $order->status = Order::STATUS_PREPARING;
            $order->save();
        }
    }
}
