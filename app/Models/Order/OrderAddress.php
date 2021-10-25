<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    /** @var string  */
    protected $table = 'order_address';

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
