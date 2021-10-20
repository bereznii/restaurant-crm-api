<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Services\GoogleDistanceMatrix\GoogleClient;
use Illuminate\Support\Facades\Log;

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

        if (!isset($delivery)) {
            throw new \RuntimeException(__METHOD__ . ': Поездка не найдена');
        }

        // Обновляем статус нужному заказу в поездке
        DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id],
            ['restaurant', '=', $validated['restaurant']],
            ['status', '=', DeliveryOrder::STATUS_ON_WAY],
            ['iiko_order_id', '=', $orderUuid],
        ])->update([
            'status' => DeliveryOrder::STATUS_DELIVERED,
            'delivered_at' => date('Y-m-d H:i:s'),
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        Log::channel('mobile')->debug("{$userId} | Закрыл заказ {$orderUuid}");

        // Берем заказы с текущей поездки по порядку доставки
        $remainingOrdersToDeliver = DeliveryOrder::where([
            ['delivery_id', '=', $delivery->id]
        ])->orderBy('delivered_at', 'asc')->get();

        // Проверяем остались ли ещё не доставленные заказы в поездке
        if ($remainingOrdersToDeliver->where('status', DeliveryOrder::STATUS_ON_WAY)->first() === null) {
            // Отмечаем поездку завершенной, считаем расстояния
            $delivery->status = Delivery::STATUS_DELIVERED;

//            $distances = (new GoogleClient())->getDistances($remainingOrdersToDeliver, $delivery->location);
            $distancesFromDirections = (new GoogleClient())->getDistancesFromDirections($remainingOrdersToDeliver, $delivery->location);

            $delivery->delivery_distance = $distancesFromDirections['deliveryDistance'];
            $delivery->return_distance = $distancesFromDirections['returnDistance'];
            $delivery->save();
        }
    }
}
