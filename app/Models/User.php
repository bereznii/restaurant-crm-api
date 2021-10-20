<?php

namespace App\Models;

use App\Models\iiko\CourierIiko;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    public const ROLE_COURIER = 'courier';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'kitchen_code',
        'phone',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasManyThrough
     */
    public function locations()
    {
        return $this->hasManyThrough(
            Location::class,
            UserLocation::class,
            'user_id',
            'id',
            'id',
            'location_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locationsIds()
    {
        return $this->hasMany(UserLocation::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function kitchen()
    {
        return $this->hasOne(Kitchen::class, 'code', 'kitchen_code');
    }

    /**
     * @return HasOne
     */
    public function iiko()
    {
        return $this->hasOne(CourierIiko::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function coordinates()
    {
        return $this->hasOne(UserCoordinate::class, 'user_id', 'id');
    }

    /**
     * @param $value
     * @return string
     */
    public function getIikoIdAttribute($value)
    {
        return $this->iiko?->iiko_id;
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

    /**
     * @param Builder $query
     * @param string $relation
     * @param string $column
     * @param string $operator
     * @param $value
     * @return Builder
     */
    public function scopeFilterWhereRelation($query, string $relation, string $column, string $operator, $value)
    {
        return isset($value)
            ? $query->whereRelation($relation, $column, $operator, $value)
            : $query;
    }
}
