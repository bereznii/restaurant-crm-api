<?php

namespace App\Models\iiko;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierIiko extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courier_iiko';

    /**
     * @var string[]
     */
    protected $fillable = [
        'restaurant',
        'user_id',
        'iiko_id',
        'created_at',
        'updated_at',
    ];
}
