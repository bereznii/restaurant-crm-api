<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;

    public const STATUS_WAITING = 'waiting';
    public const STATUS_ON_WAY = 'on_way';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CLOSED = 'closed';

    public const RANGE_TYPE_WITHIN_CITY = 'within_city';
    public const RANGE_TYPE_OUTSIDE_CITY = 'outside_city';

    /**
     * @var array
     */
    protected $fillable = [
        'restaurant',
        'address',
        'iiko_order_id',
        'range_type',
    ];
}
