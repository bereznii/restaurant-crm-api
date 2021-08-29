<?php

namespace App\Services\GoogleDistanceMatrix;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;

class GoogleClient
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private const API_UNITS = 'metric';

    /** @var string */
    private $token;

    /** @var MatrixService */
    private $matrixService;

    /**
     *
     */
    public function __construct()
    {
        $this->token = config('google.distance-matrix.token');
        $this->matrixService = new MatrixService();
    }

    /**
     * @param Collection $deliveredOrders
     * @param Location $location
     * @return array
     */
    public function getDistances(Collection $deliveredOrders, Location $location): array
    {
        $origins = $this->getOriginsFromOrders($deliveredOrders, $location->address);
        $destinations = $this->getDestinationsFromOrders($deliveredOrders, $location->address);

        $response = Http::get(self::API_URL, [
            'origins' => $origins,
            'destinations' => $destinations,
            'units' => self::API_UNITS,
            'key' => $this->token,
        ])->json();

        return $this->parseResponse($response);
    }

    /**
     * @param Collection $deliveredOrders
     * @param string $locationAddress
     * @return string
     */
    private function getOriginsFromOrders(Collection $deliveredOrders, string $locationAddress): string
    {
        $addresses = array_column($deliveredOrders->toArray(), 'address');

        return $locationAddress . '|' . implode('|', $addresses);
    }

    /**
     * @param Collection $deliveredOrders
     * @param string $locationAddress
     * @return string
     */
    private function getDestinationsFromOrders(Collection $deliveredOrders, string $locationAddress): string
    {
        $addresses = array_column($deliveredOrders->toArray(), 'address');

        return implode('|', $addresses) . '|' . $locationAddress;
    }

    /**
     * @param array $response
     * @return array
     */
    private function parseResponse(array $response): array
    {
        return $this->matrixService->calculateDistances($response['rows']);
    }
}
