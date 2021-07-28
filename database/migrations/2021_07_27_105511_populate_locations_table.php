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
                'city_sync_id' => 'lviv',
                'street' => 'ул. Кульпарковская',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Кульпарковская Сушиго',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Кульпарковская',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Мазепы Смаки',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Мазепы',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Мазепы Сушиго',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Мазепы',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Садибна Смаки',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Садыбная',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Садибна Сушиго',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Садыбная',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Белоцерковская Смаки',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Белоцерковская',
                'house_number' => '2А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Белоцерковская Сушиго',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Белоцерковская',
                'house_number' => '2А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Широкая Смаки',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Широкая',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Широкая Сушиго',
                'city_sync_id' => 'lviv',
                'street' => 'ул. Широкая',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Николаев Смаки',
                'city_sync_id' => 'mykolaiv',
                'street' => 'ул. Пивденна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Николаев Сушиго',
                'city_sync_id' => 'mykolaiv',
                'street' => 'ул. Пивденна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Сумы Смаки',
                'city_sync_id' => 'sumy',
                'street' => 'проспект Шевченко',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Сумы Сушиго',
                'city_sync_id' => 'sumy',
                'street' => 'проспект Шевченко',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Ивано-Франковск Смаки',
                'city_sync_id' => 'ivano-frankivsk',
                'street' => 'ул. Береговая',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Ивано-Франковск Сушиго',
                'city_sync_id' => 'ivano-frankivsk',
                'street' => 'ул. Береговая',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Хмельницький Смаки',
                'city_sync_id' => 'khmelnytskyi',
                'street' => 'ул. Гагарина',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Хмельницький Сушиго',
                'city_sync_id' => 'khmelnytskyi',
                'street' => 'ул. Гагарина',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Херсон Смаки',
                'city_sync_id' => 'kherson',
                'street' => 'ул. Рабочая',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Херсон Сушиго',
                'city_sync_id' => 'kherson',
                'street' => 'ул. Рабочая',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Ровно Смаки',
                'city_sync_id' => 'rivne',
                'street' => 'проспект Мира',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Ровно Сушиго',
                'city_sync_id' => 'rivne',
                'street' => 'проспект Мира',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Луцк Смаки',
                'city_sync_id' => 'lutsk',
                'street' => 'ул. Огиенко',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Луцк Сушиго',
                'city_sync_id' => 'lutsk',
                'street' => 'ул. Огиенко',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Винница Смаки',
                'city_sync_id' => 'vinnytsia',
                'street' => 'ул. Глеба Успенского',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Винница Сушиго',
                'city_sync_id' => 'vinnytsia',
                'street' => 'ул. Глеба Успенского',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Тернополь Смаки',
                'city_sync_id' => 'ternopil',
                'street' => 'ул. Гоголя',
                'house_number' => '2',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Тернополь Сушиго',
                'city_sync_id' => 'ternopil',
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
