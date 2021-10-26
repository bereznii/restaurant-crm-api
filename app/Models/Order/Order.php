<?php

namespace App\Models\Order;

use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_COOKING = 'cooking';
    public const STATUS_PREPARING = 'preparing';
    public const STATUSES = [
        [
            'name' => 'new',
            'title' => 'Новый заказ',
        ],
        [
            'name' => 'cooking',
            'title' => 'Готовится',
        ],
        [
            'name' => 'preparing',
            'title' => 'Пакуется',
        ],
        [
            'name' => 'for_delivery',
            'title' => 'Доставляется',
        ],
        [
            'name' => 'closed',
            'title' => 'Успешно закрыт',
        ],
        [
            'name' => 'rejected',
            'title' => 'Отменен',
        ],
    ];

    public const TYPE_REQUESTED_TIME = 'requested_time';
    public const TYPES = [
        [
            'name' => 'soon',
            'title' => 'Ближайшее время',
        ],
        [
            'name' => 'requested_time',
            'title' => 'На определенное время',
        ],
    ];

    public const PAYMENT_TYPES = [
        [
            'name' => 'online',
            'title' => 'Онлайн',
        ],
        [
            'name' => 'cash',
            'title' => 'Наличные',
        ],
        [
            'name' => 'card',
            'title' => 'Картой',
        ],
        [
            'name' => 'bonus',
            'title' => 'Бонусы',
        ],
    ];

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany
     */
    public function history()
    {
        return $this->hasMany(OrderStatus::class);
    }

    /**
     * @return HasOne
     */
    public function address()
    {
        return $this->hasOne(OrderAddress::class);
    }

    /**
     * @return HasOne
     */
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
