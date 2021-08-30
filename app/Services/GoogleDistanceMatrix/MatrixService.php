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
        // MATRIX[n-1][n-1], где размер матрицы N x N
        // TODO: учесть вероятность невалидного адреса и как результат отсутсвия результата
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
        // [0][0] + [1][1] + ... + [n-2][n-2] (чтобы исключить последнюю дистанцию (возврат на кухню)), где размер матрицы N x N

        $distance = 0;
        foreach (range(0,$rowsCount - 2) as $index) {
            // TODO: учесть вероятность невалидного адреса и как результат отсутсвия результата
            $distance += $rows[$index]['elements'][$index]['distance']['value'];
        }

        return $distance;
    }
}
