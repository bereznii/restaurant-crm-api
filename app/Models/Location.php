<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    use HasFactory;

    /** @var string */
    public const SMAKI_MAKI_RESTAURANT = 'smaki';

    /** @var string */
    public const SUSHI_GO_RESTAURANT = 'go';

    /**
     * @return HasOne
     */
    public function city()
    {
        return $this->hasOne(City::class, 'sync_id', 'city_sync_id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getAddressAttribute($value)
    {
        return "{$this->city->name_ua}, {$this->street_ua} {$this->house_number}";
    }
}
