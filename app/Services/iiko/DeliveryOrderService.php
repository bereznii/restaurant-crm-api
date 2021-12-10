<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Services\GoogleDistanceMatrix\GoogleClient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
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
        $delivery = Delivery::where('id', '=', Auth::user()->courierCurrentDeliveryId)
            ->orderBy('id', 'desc')
            ->first();

        if (!isset($delivery)) {
            throw new \RuntimeException(__METHOD__ . ': Поездка не найдена. Текущий статус курьера ' . Auth::id() . ': ' . Auth::user()->courierStatus . ' (' . Auth::user()->courierCurrentDeliveryId . ')');
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
            $this->closeCurrentDelivery($delivery, $remainingOrdersToDeliver);
            Log::info(Auth::id() . ' | Поездка закрылась. Причина курьер закрыл последний заказ');
        }
    }

    /**
     * @param Delivery $delivery
     * @param Collection $ordersInDelivery
     */
    public static function closeCurrentDelivery(Delivery $delivery, Collection $ordersInDelivery)
    {
        // Отмечаем поездку завершенной, считаем расстояния
        $delivery->status = Delivery::STATUS_DELIVERED;

        // Считаем расстояния
        $distancesFromDirections = (new GoogleClient())->getDistancesFromDirections(
            $ordersInDelivery->where('status', DeliveryOrder::STATUS_DELIVERED),// Считаем дистанцию только для доставленных
            $delivery->location
        );

        $delivery->delivery_distance = $distancesFromDirections['deliveryDistance'];
        $delivery->return_distance = $distancesFromDirections['returnDistance'];
        $delivery->save();

        if ($delivery->save()) {
            Auth::user()->setStatusWaiting();
        }
    }
}
