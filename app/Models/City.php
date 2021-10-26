<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    /**
     *
     */
    public const CITIES_UA = [
        "львів",
        "львов",
        "миколаїв",
        "николаев",
        "суми",
        "сумы",
        "хмельницький",
        "хмельницкий",
        "херсон",
        "херсон",
        "рівне",
        "ровно",
        "луцьк",
        "луцк",
        "вінниця",
        "винница",
        "тернопіль",
        "тернополь",
        "івано-франківськ",
        "ивано-франковск",
    ];
}
