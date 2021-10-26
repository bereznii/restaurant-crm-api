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
     * @return mixed
     */
    public function update(array $validated, OrderItem $orderItem, int $userId)
    {
        DB::beginTransaction();

        try {
            $orderItem->cook_id = $userId;
            $orderItem->status = $validated['status'];
            $orderItem->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return OrderItem::where('id', $orderItem->id)
            ->with('product', 'order')
            ->first();
    }
}
