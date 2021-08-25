<?php

namespace App\Services;

use App\Models\iiko\CourierIiko;

class CourierService
{
    /**
     * @param int $userId
     * @param string $iikoUuid
     */
    public function storeIikoData(int $userId, string $iikoUuid): void
    {
        $iikoRecord = new CourierIiko();
        $iikoRecord->user_id = $userId;
        $iikoRecord->iiko_id = $iikoUuid;
        $iikoRecord->save();
    }

    /**
     * @param int $userId
     * @param string $iikoUuid
     */
    public function updateIikoData(int $userId, string $iikoUuid): void
    {
        $iikoRecord = CourierIiko::where('user_id', $userId)->first() ?? new CourierIiko();
        $iikoRecord->user_id = $userId;
        $iikoRecord->iiko_id = $iikoUuid;
        $iikoRecord->save();
    }
}
