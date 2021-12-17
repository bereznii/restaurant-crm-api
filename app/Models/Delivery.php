<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Delivery extends Model
{
    use HasFactory;

    public const STATUS_ON_WAY = 'on_way';
    public const STATUS_DELIVERED = 'delivered';

    public const RANGE_TYPE_WITHIN_CITY = 'within_city';
    public const RANGE_TYPE_OUTSIDE_CITY = 'outside_city';

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(DeliveryOrder::class, 'delivery_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'delivery_terminal_id', 'delivery_terminal_id');
    }

    /**
     * @param Builder $query
     * @param string $column
     * @param string $operator
     * @param $value
     * @return Builder
     */
    public function scopeFilterWhere($query, string $column, string $operator, $value)
    {
        return isset($value)
            ? $query->where($column, $operator, $value)
            : $query;
    }
}
