<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        [
            'name' => 'new',
            'title' => 'Новый заказ',
        ],
        [
            'name' => 'processing',
            'title' => 'Обрабатывается оператором',
        ],
        [
            'name' => 'waiting',
            'title' => 'В ожидании',
        ],
        [
            'name' => 'for_kitchen',
            'title' => 'Передан на кухню',
        ],
        [
            'name' => 'cooking',
            'title' => 'Готовится',
        ],
        [
            'name' => 'preparing',
            'title' => 'Формируется',
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
            'title' => 'Отказ',
        ],
    ];

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
    ];
}
