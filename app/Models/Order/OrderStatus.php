<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    /** @var bool  */
    public $timestamps = false;

    /** @var string[]  */
    protected $fillable = [
        'status',
        'user_id',
        'set_at',
    ];
}
