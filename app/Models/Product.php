<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /** @var string  */
    protected $primaryKey = 'id';
    /** @var string  */
    protected $keyType = 'string';
    /** @var bool  */
    public $incrementing = false;

    /** @var string[]  */
    protected $appends = [
        'image'
    ];

    /** @var string[] */
    protected $fillable = [
        'price_old'
    ];

    /**
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {
        return $this->hasOne(ProductTypes::class, 'sync_id', 'type_sync_id');
    }

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(ProductCategories::class, 'sync_id', 'category_sync_id');
    }

    /**
     * @return string|null
     */
    public function getImageAttribute(): ?string
    {
        $image = $this->getFirstMedia();

        return isset($image)
            ? $image->getFullUrl()
            : url('/images/default.jpg');
    }

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
