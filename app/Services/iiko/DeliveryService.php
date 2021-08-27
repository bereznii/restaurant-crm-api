<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    /**
     * @param string $courierUuid
     * @param int $userId
     * @param array $validated
     * @return array
     */
    public function store(string $courierUuid, int $userId, array $validated): array
    {
        $success = false;
        DB::beginTransaction();

        try {
            $delivery = new Delivery();
            $delivery->iiko_courier_id = $courierUuid;
            $delivery->user_id = $userId;
            $delivery->distance = 0;
            $delivery->started_at = date('Y-m-d H:i:s');
            if ($delivery->save()) {
                $success = $this->createRelatedDeliveryOrders($delivery, $validated);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $success = false;
        }

        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return [
            'success' => $success
        ];
    }

    /**
     * @param Delivery $delivery
     * @param array $validated
     * @return bool
     */
    private function createRelatedDeliveryOrders(Delivery $delivery, array $validated): bool
    {
        return (bool) $delivery->orders()
            ->createMany(array_map(function ($order) {
                return [
                    'restaurant' => $order['restaurant'],
                    'iiko_order_id' => $order['order_uuid'],
                    'range_type' => Delivery::RANGE_TYPE_WITHIN_CITY,//TODO: определять
                ];
            }, $validated['orders']));
    }
}
