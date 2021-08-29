<?php

namespace App\Services\iiko;

use App\Models\City;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

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
            $delivery->delivery_terminal_id = $validated['delivery_terminal_id'];
            $delivery->iiko_courier_id = $courierUuid;
            $delivery->user_id = $userId;
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
                    'address' => $this->formatAddress($order['address']),
                    'range_type' => in_array($order['address']['city'], City::CITIES_UA)
                        ? Delivery::RANGE_TYPE_WITHIN_CITY
                        : Delivery::RANGE_TYPE_OUTSIDE_CITY,
                ];
            }, $validated['orders']));
    }

    /**
     * @param array $address
     * @return string
     */
    private function formatAddress(array $address): string
    {
        $formattedAddressString = '';

        $formattedAddressString .= "{$address['city']}, ";
        $formattedAddressString .= "{$address['street']} ";
        $formattedAddressString .= "{$address['home']}";

        return $formattedAddressString;
    }

    /**
     * @param string $courierIikoId
     * @return Collection|null
     */
    public function existingDeliveryForCourier(string $courierIikoId): ?Collection
    {
        return Delivery::with('orders')
            ->where([
                ['iiko_courier_id', '=', $courierIikoId],
            ])
            ->orderBy('id', 'desc')
            ->first()
            ?->orders;
    }
}
