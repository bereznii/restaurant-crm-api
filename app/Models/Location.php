<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /** @var string */
    public const SMAKI_MAKI_RESTAURANT = 'smaki';

    /** @var string */
    public const SUSHI_GO_RESTAURANT = 'go';
}
