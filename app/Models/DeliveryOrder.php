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
