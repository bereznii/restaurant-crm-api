<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public const CLIENT_SOURCES = [
        [
            'name' => 'website',
            'title' => 'Сайт',
        ],
        [
            'name' => 'mobile',
            'title' => 'Мобилка',
        ]
    ];

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
