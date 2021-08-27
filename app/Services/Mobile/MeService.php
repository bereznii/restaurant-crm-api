<?php

namespace App\Services\Mobile;

use App\Models\UserCoordinate;
use Illuminate\Support\Facades\Auth;

class MeService
{
    /**
     * @param array $validated
     * @return bool
     */
    public function updateCoordinates(array $validated): bool
    {
        return (bool) UserCoordinate::updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]
        );
    }
}
