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
    public function update(string $orderUuid, array $validated)
    {
        return $this->setOrderDelivered($orderUuid, $validated);
    }

    /**
     * @param string $orderUuid
     * @param array $validated
     * @return Collection
     * @throws \Exception
     */
    public function setOrderDelivered(string $orderUuid, array $validated): Collection
    {
        return collect(
            $this->iikoServiceInterface->setOrderDelivered(Auth::user()->iikoId, Auth::id(), $orderUuid, $validated)
        );
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function getOrdersForCourier(): Collection
    {
        return collect(
            $this->iikoServiceInterface->getCourierOrders(Auth::user()->iikoId)
        );
    }
}
