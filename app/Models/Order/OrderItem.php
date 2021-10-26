<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROCESS = 'in_process';
    public const STATUS_READY = 'ready';
    public const STATUSES = [
        [
            'name' => 'new',
            'title' => 'Новый',
        ],
        [
            'name' => 'in_process',
            'title' => 'Готовится',
        ],
        [
            'name' => 'ready',
            'title' => 'Приготовлен',
        ]
    ] ;

    /** @var string[]  */
    protected $touches = ['order'];

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
