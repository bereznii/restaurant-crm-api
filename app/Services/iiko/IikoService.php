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
    public function getOrdersForCourier(): Collection
    {
        $response = $this->iikoServiceInterface->getCourierOrders(Auth::user()->iikoId);

        return collect($response);
    }

    /**
     * @param string $orderUuid
     * @param array $validated
     * @return Collection
     * @throws \Exception
     */
    public function setOrderDelivered(string $orderUuid, array $validated): Collection
    {
        $response = $this->iikoServiceInterface->setOrderDelivered(Auth::user()->iikoId, $orderUuid, $validated);

        return collect($response);
    }
}
