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

    /**
     *
     */
    public function __construct()
    {
        $this->token = config('google.distance-matrix.token');
    }

    /**
     * @param Collection $deliveredOrders
     * @param Location $location
     * @return array
     */
    public function getDistances(Collection $deliveredOrders, Location $location): array
    {
        $distances = [];

        $origins = $this->getOriginsFromOrders($deliveredOrders, $location->address);
        $destinations = $this->getDestinationsFromOrders($deliveredOrders, $location->address);

        $response = Http::get(self::API_URL, [
            'origins' => $origins,
            'destinations' => $destinations,
            'units' => self::API_UNITS,
            'key' => $this->token,
        ])->json();

        $distances = $this->parseResponse($response);

        return $distances;
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
        $matrixRows = count($response['rows']);

        $returnDistance = $response['rows'][$matrixRows - 1]['elements'][$matrixRows - 1];
        $deliveryDistance = 0;//TODO: найти универсальную формулу расчёта

        return [
            'deliveryDistance' => $deliveryDistance,
            'returnDistance' => $returnDistance,
        ];
    }
}
