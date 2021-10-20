<?php

namespace App\Services\GoogleDistanceMatrix;

use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleClient
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private const API_UNITS = 'metric';

    private const DIRECTIONS_API_URL = 'https://maps.googleapis.com/maps/api/directions/json';

    /** @var string */
    private $token;

    /** @var MatrixService */
    private $matrixService;

    /** @var DirectionsService */
    private $directionsService;

    /**
     *
     */
    public function __construct()
    {
        $this->token = config('google.distance-matrix.token');
        $this->matrixService = new MatrixService();
        $this->directionsService = new DirectionsService();
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
        ]);

        Log::channel('outgoing')->info(Auth::id() . ' | ' . self::API_URL . ' Request: ' . json_encode(['origins' => $origins, 'destinations' => $destinations,]));
        Log::channel('outgoing')->info(Auth::id() . ' | ' . self::API_URL . ' Response: ' . $response->body());

        return $this->parseResponse($response->json());
    }

    /**
     * @param Collection $deliveredOrders
     * @param string $locationAddress
     * @return string
     */
    private function getOriginsFromOrders(Collection $deliveredOrders, string $locationAddress): string
    {
        $addresses = array_map(fn ($item) => "{$item['latitude']},{$item['longitude']}", $deliveredOrders->toArray());

        return $locationAddress . '|' . implode('|', $addresses);
    }

    /**
     * @param Collection $deliveredOrders
     * @param string $locationAddress
     * @return string
     */
    private function getDestinationsFromOrders(Collection $deliveredOrders, string $locationAddress): string
    {
        $addresses = array_map(fn ($item) => "{$item['latitude']},{$item['longitude']}", $deliveredOrders->toArray());

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

    // DIRECTIONS

    /**
     * @param Collection $deliveredOrders
     * @param Location $location
     * @return array
     */
    public function getDistancesFromDirections(Collection $deliveredOrders, Location $location): array
    {
        $waypoints = implode('|', array_map(fn ($item) => "{$item['latitude']},{$item['longitude']}", $deliveredOrders->toArray()));

        $response = Http::get(self::DIRECTIONS_API_URL, [
            'origin' => $location->address,
            'destination' => $location->address,
            'waypoints' => $waypoints,
            'units' => self::API_UNITS,
            'key' => $this->token,
        ]);

        Log::channel('outgoing')->info(Auth::id() . ' | ' . self::API_URL . ' Request: ' . json_encode(['origin' => $location->address, 'destination' => $location->address, 'waypoints' => $waypoints,],JSON_UNESCAPED_UNICODE));
        Log::channel('outgoing')->info(Auth::id() . ' | ' . self::API_URL . ' Response: successfull? ' . $response->successful());

        return $this->parseResponseFromDirections($response->json());
    }

    /**
     * @param array $response
     * @return array
     */
    private function parseResponseFromDirections(array $response): array
    {
        return $this->directionsService->calculateDistances($response);
    }
}
