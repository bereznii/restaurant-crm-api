<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class IikoService
{
    /**
     * @param IikoServiceInterface $iikoServiceInterface
     */
    public function __construct(
        private IikoServiceInterface $iikoServiceInterface,
    ) {}

    /**
     * @return Collection
     * @throws \Exception
     */
    public function update(string $courierIikoId, int $userId, string $orderUuid, array $validated)
    {
        return $this->setOrderDelivered($courierIikoId, $userId, $orderUuid, $validated);
    }

    /**
     * @param string $courierIikoId
     * @param int $userId
     * @param string $orderUuid
     * @param array $validated
     * @return Collection
     * @throws \Exception
     */
    public function setOrderDelivered(string $courierIikoId, int $userId, string $orderUuid, array $validated): Collection
    {
        return collect(
            $this->iikoServiceInterface->setOrderDelivered($courierIikoId, $userId, $orderUuid, $validated)
        );
    }

    /**
     * @param string $courierIikoId
     * @return Collection
     * @throws \Exception
     */
    public function getOrdersForCourier(string $courierIikoId): Collection
    {
        return collect(
            $this->iikoServiceInterface->getCourierOrders($courierIikoId)
        );
    }
}
