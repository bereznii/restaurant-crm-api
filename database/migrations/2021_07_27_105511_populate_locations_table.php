<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class PopulateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $date = date('Y-m-d H:i:s');

        DB::table('locations')->insert([
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Кульпарковская Смаки',
                'city' => 'Львов',
                'street' => 'ул. Кульпарковская',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Кульпарковская Сушиго',
                'city' => 'Львов',
                'street' => 'ул. Кульпарковская',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Мазепы Смаки',
                'city' => 'Львов',
                'street' => 'ул. Мазепы',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Мазепы Сушиго',
                'city' => 'Львов',
                'street' => 'ул. Мазепы',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Садибна Смаки',
                'city' => 'Львов',
                'street' => 'ул. Садыбная',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Садибна Сушиго',
                'city' => 'Львов',
                'street' => 'ул. Садыбная',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Белоцерковская Смаки',
                'city' => 'Львов',
                'street' => 'ул. Белоцерковская',
                'house_number' => '2А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Белоцерковская Сушиго',
                'city' => 'Львов',
                'street' => 'ул. Белоцерковская',
                'house_number' => '2А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Широкая Смаки',
                'city' => 'Львов',
                'street' => 'ул. Широкая',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Широкая Сушиго',
                'city' => 'Львов',
                'street' => 'ул. Широкая',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Николаев Смаки',
                'city' => 'Николаев',
                'street' => 'ул. Пивденна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Николаев Сушиго',
                'city' => 'Николаев',
                'street' => 'ул. Пивденна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Сумы Смаки',
                'city' => 'Сумы',
                'street' => 'проспект Шевченко',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Сумы Сушиго',
                'city' => 'Сумы',
                'street' => 'проспект Шевченко',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Ивано-Франковск Смаки',
                'city' => 'Ивано-Франковск',
                'street' => 'ул. Береговая',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Ивано-Франковск Сушиго',
                'city' => 'Ивано-Франковск',
                'street' => 'ул. Береговая',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Хмельницький Смаки',
                'city' => 'Хмельницький',
                'street' => 'ул. Гагарина',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Хмельницький Сушиго',
                'city' => 'Хмельницький',
                'street' => 'ул. Гагарина',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Херсон Смаки',
                'city' => 'Херсон',
                'street' => 'ул. Рабочая',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Херсон Сушиго',
                'city' => 'Херсон',
                'street' => 'ул. Рабочая',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Ровно Смаки',
                'city' => 'Ровно',
                'street' => 'проспект Мира',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Ровно Сушиго',
                'city' => 'Ровно',
                'street' => 'проспект Мира',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Луцк Смаки',
                'city' => 'Луцк',
                'street' => 'ул. Огиенко',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Луцк Сушиго',
                'city' => 'Луцк',
                'street' => 'ул. Огиенко',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Винница Смаки',
                'city' => 'Винница',
                'street' => 'ул. Глеба Успенского',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Винница Сушиго',
                'city' => 'Винница',
                'street' => 'ул. Глеба Успенского',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Тернополь Смаки',
                'city' => 'Тернополь',
                'street' => 'ул. Гоголя',
                'house_number' => '2',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Тернополь Сушиго',
                'city' => 'Тернополь',
                'street' => 'ул. Гоголя',
                'house_number' => '2',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('locations')->truncate();
    }
}
