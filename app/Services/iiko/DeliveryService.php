<?php

namespace App\Services\iiko;

use App\Models\City;
use App\Models\Delivery;
use App\Models\iiko\CourierIiko;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                $success = $success && $this->setCourierStatus($userId, $delivery->id);
            }
        } catch (\Exception $e) {
            Log::error(Auth::id() . ' | ' . $e->getMessage());
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
                    'range_type' => $this->getRangeType($order['address']),
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
     * @param array $address
     * @return string
     */
    private function getRangeType(array $address): string
    {
        $inCity = false;
        $lowCaseCity = mb_strtolower($address['city']);

        foreach (City::CITIES_UA as $city) {
            if (mb_strpos($lowCaseCity, $city) !== false) {
                $inCity = true;
            }
        }

        return $inCity
            ? Delivery::RANGE_TYPE_WITHIN_CITY
            : Delivery::RANGE_TYPE_OUTSIDE_CITY;
    }

    /**
     * @param int $userId
     * @return mixed
     */
    private function setCourierStatus(int $userId, int $deliveryId): bool
    {
        $courierRecord = CourierIiko::where('user_id', '=', $userId)->first();
        $courierRecord->status = User::COURIER_STATUS_ON_DELIVERY;
        $courierRecord->current_delivery_id = $deliveryId;
        return (bool) $courierRecord->save();
    }

    /**
     * @return Collection|null
     */
    public function existingDeliveryForCourier(): ?Collection
    {
        return Delivery::where('id', Auth::user()->courierCurrentDeliveryId)->first()
            ?->orders;
    }
}
