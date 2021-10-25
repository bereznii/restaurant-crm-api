<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    /** @var string[]  */
    protected $touches = ['order'];

    /**
     * Get the post that the comment belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
