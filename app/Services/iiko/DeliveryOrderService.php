<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use App\Models\DeliveryOrder;

class DeliveryOrderService
{
    /**
     * @param string $courierIikoUuid
     * @param int $userId
     * @param string $orderUuid
     * @param array $validated
     * @return void
     */
    public function setAsDelivered(string $courierIikoUuid, int $userId, string $orderUuid, array $validated): void
    {
        $delivery = Delivery::where([
                ['iiko_courier_id', '=', $courierIikoUuid],
                ['status', '=', Delivery::STATUS_ON_WAY],
                ['user_id', '=', $userId],
            ])->first();

        DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id],
            ['restaurant', '=', $validated['restaurant']],
            ['status', '=', DeliveryOrder::STATUS_ON_WAY],
            ['iiko_order_id', '=', $orderUuid],
        ])->update([
            'status' => DeliveryOrder::STATUS_DELIVERED,
            'delivered_at' => date('Y-m-d H:i:s'),
        ]);

        $remainingOrdersToDeliver = DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id],
            ['status', '=', DeliveryOrder::STATUS_ON_WAY],
        ])->exists();

        if (!$remainingOrdersToDeliver) {
            //TODO: Посчитать маршрут и записать
        }
    }
}
