<?php

namespace App\Services\GoogleDistanceMatrix;

class DirectionsService
{
    /**
     * @param array $response
     * @return array
     */
    public function calculateDistances(array $response): array
    {
        $legsCount = count($response['routes'][0]['legs']);

        $returnDistance = $this->getReturnDistance($response['routes'][0]['legs'], $legsCount);
        $deliveryDistance = $this->getDeliveryDistance($response['routes'][0]['legs'], $legsCount);

        return [
            'deliveryDistance' => $deliveryDistance,
            'returnDistance' => $returnDistance,
        ];
    }

    /**
     * @param array $legs
     * @param int $legsCount
     * @return int
     */
    private function getReturnDistance(array $legs, int $legsCount): int
    {
        return (int) $legs[$legsCount - 1]['distance']['value'] ?? 0;
    }

    /**
     * @param array $legs
     * @param int $legsCount
     * @return int
     */
    private function getDeliveryDistance(array $legs, int $legsCount): int
    {
        unset($legs[$legsCount - 1]);

        $distance = 0;
        foreach ($legs as $leg) {
            $distance += $leg['distance']['value'] ?? 0;
        }

        return $distance;
    }
}
