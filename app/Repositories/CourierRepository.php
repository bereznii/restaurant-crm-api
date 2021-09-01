<?php

namespace App\Repositories;

use App\Models\iiko\CourierIiko;
use App\Models\UserCoordinate;

class CourierRepository extends AbstractRepository
{
    /** @var string */
    protected string $modelClass = CourierIiko::class;

    /**
     * @param string $courierIikoId
     * @return mixed
     */
    public function getCoordinates(string $courierIikoId)
    {
        $userId = $this->_getInstance()
            ->where('iiko_id', '=', $courierIikoId)
            ->firstOrFail()?->user_id;

        $coordinatesModel = UserCoordinate::where('user_id', '=', $userId)->firstOrFail();

        return [
            'latitude' => $coordinatesModel->latitude,
            'longitude' => $coordinatesModel->longitude,
        ];
    }
}
