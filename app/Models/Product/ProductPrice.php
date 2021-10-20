<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

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
