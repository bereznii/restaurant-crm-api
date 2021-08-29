<?php

namespace App\Services\GoogleDistanceMatrix;

class MatrixService
{
    /**
     * @param array $rows
     * @return array
     */
    public function calculateDistances(array $rows): array
    {
        $rowsCount = count($rows);

        $returnDistance = $this->getReturnDistance($rows, $rowsCount);
        $deliveryDistance = $this->getDeliveryDistance($rows, $rowsCount);

        return [
            'deliveryDistance' => $deliveryDistance,
            'returnDistance' => $returnDistance,
        ];
    }

    /**
     * @param array $rows
     * @param int $rowsCount
     * @return float
     */
    private function getReturnDistance(array $rows, int $rowsCount): float
    {
        // MATRIX[n][n], где размер матрицы N x N
        $neededElement = $rows[$rowsCount - 1]['elements'][$rowsCount - 1];

        return $neededElement['distance']['value'];
    }

    /**
     * @param array $rows
     * @param int $rowsCount
     * @return float
     */
    private function getDeliveryDistance(array $rows, int $rowsCount): float
    {
        // [0][0] + [1][1] + ... + [n][n], где размер матрицы N x N
        //TODO: колонку в таблице заменить на integer

        $distance = 0;
        foreach (range(0,$rowsCount - 1) as $index) {
            $distance += $rows[$index]['elements'][$index]['distance']['value'];
        }

        return $distance;
    }
}
