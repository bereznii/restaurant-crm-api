<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'sync_id', 'city_sync_id');
    }
}
