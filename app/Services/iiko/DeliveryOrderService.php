<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Services\GoogleDistanceMatrix\GoogleClient;

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
        // Получаем поездку
        $delivery = Delivery::where([
                ['iiko_courier_id', '=', $courierIikoUuid],
                ['status', '=', Delivery::STATUS_ON_WAY],
                ['user_id', '=', $userId],
            ])->orderBy('id', 'desc')->first();

        // Обновляем статус нужному заказу в поездке
        DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id],
            ['restaurant', '=', $validated['restaurant']],
            ['status', '=', DeliveryOrder::STATUS_ON_WAY],
            ['iiko_order_id', '=', $orderUuid],
        ])->update([
            'status' => DeliveryOrder::STATUS_DELIVERED,
            'delivered_at' => date('Y-m-d H:i:s'),
        ]);

        // Проверяем остались ли ещё не доставленные заказы в поездке
        $remainingOrdersToDeliver = DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id]
        ])->orderBy('delivered_at', 'asc')->get();

        if (!$remainingOrdersToDeliver->has('status', DeliveryOrder::STATUS_ON_WAY)) {
            // Отмечаем поездку завершенной, считаем расстояния
//            $delivery->status = Delivery::STATUS_DELIVERED;
//
//            $distances = (new GoogleClient())->getDistances($remainingOrdersToDeliver, $delivery->location);
//
//            $delivery->delivery_distance = $distances['deliveryDistance'];
//            $delivery->return_distance = $distances['returnDistance'];
//            $delivery->save();
        }
    }
}
