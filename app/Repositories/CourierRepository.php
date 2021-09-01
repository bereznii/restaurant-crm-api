<?php

namespace App\Repositories;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Models\iiko\CourierIiko;
use App\Models\UserCoordinate;

class CourierRepository extends AbstractRepository
{
    /** @var string */
    protected string $modelClass = CourierIiko::class;

    /**
     * @param string $orderId
     * @return array
     */
    public function getCoordinates(string $orderId): array
    {
        $deliveryId = DeliveryOrder::where([
                ['iiko_order_id', '=', $orderId],
                ['status', '=', DeliveryOrder::STATUS_ON_WAY]
            ])
            ->orderBy('id', 'desc')
            ->firstOrFail()?->delivery_id;

        $userId = Delivery::where('id', '=', $deliveryId)->firstOrfail()?->user_id;

        $coordinatesModel = UserCoordinate::where('user_id', '=', $userId)->firstOrFail();

        return [
            'latitude' => $coordinatesModel->latitude,
            'longitude' => $coordinatesModel->longitude,
        ];
    }
}
