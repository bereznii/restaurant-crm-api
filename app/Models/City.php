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
        "Львів",
        "Львов",
        "Миколаїв",
        "Николаев",
        "Суми",
        "Сумы",
        "Хмельницький",
        "Хмельницький",
        "Херсон",
        "Херсон",
        "Рівне",
        "Ровно",
        "Луцьк",
        "Луцк",
        "Вінниця",
        "Винница",
        "Тернопіль",
        "Тернополь",
        "Івано-Франківськ",
        "Ивано-Франковск",
    ];
}
