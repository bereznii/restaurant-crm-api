<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('street_ua')->after('street');
            $table->uuid('delivery_terminal_id')->after('id');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->uuid('delivery_terminal_id')->after('id');
        });

        DB::table('locations')->truncate();
        $date = date('Y-m-d H:i:s');
        DB::table('locations')->insert([
            [
                'delivery_terminal_id' => 'aa15c7b2-768f-dbf1-016c-8fc96e6aa61b',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Кульпарківська',
                'city_sync_id' => 'lviv',
                'street' => 'улица Кульпарковская',
                'street_ua' => 'вулиця Кульпарківська',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '7558329a-4e69-821f-0170-f740b7a61745',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Кульпарківська',
                'city_sync_id' => 'lviv',
                'street' => 'улица Кульпарковская',
                'street_ua' => 'вулиця Кульпарківська',
                'house_number' => '95',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '77e9b916-6bb3-32aa-0171-0490af7c04a1',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Мазепи',
                'city_sync_id' => 'lviv',
                'street' => 'улица Мазепы',
                'street_ua' => 'вулиця Мазепи',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '1fed97e5-04f4-c273-0172-92ff38d1a230',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Мазепи',
                'city_sync_id' => 'lviv',
                'street' => 'улица Мазепы',
                'street_ua' => 'вулиця Мазепи',
                'house_number' => '11В',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '2ab6bd80-13d4-fde8-0174-1112e8815de6',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Садибна',
                'city_sync_id' => 'lviv',
                'street' => 'улица Садыбная',
                'street_ua' => 'вулиця Садибна',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'f0211b21-726c-480e-0174-116765622d85',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Садибна',
                'city_sync_id' => 'lviv',
                'street' => 'улица Садыбная',
                'street_ua' => 'вулиця Садибна',
                'house_number' => '27',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'aa15c7b2-768f-dbf1-016c-8fc96e6a28ff',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Дорошенко',
                'city_sync_id' => 'lviv',
                'street_ua' => 'вулиця Дорошенка',
                'street' => 'улица Дорошенко',
                'house_number' => '77',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '1fed97e5-04f4-c273-0172-7fb0f1658acf',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Дорошенко',
                'city_sync_id' => 'lviv',
                'street_ua' => 'вулиця Дорошенка',
                'street' => 'улица Дорошенко',
                'house_number' => '77',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '018cf4d4-0e07-b00a-0172-79730f6ec4df',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Широка',
                'city_sync_id' => 'lviv',
                'street' => 'улица Широкая',
                'street_ua' => 'вулиця Широка',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '1fed97e5-04f4-c273-0172-7a379677a737',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Широка',
                'city_sync_id' => 'lviv',
                'street' => 'улица Широкая',
                'street_ua' => 'вулиця Широка',
                'house_number' => '11',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '018cf4d4-0e07-b00a-0172-e4f18ac4ce92',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Миколаїв',
                'city_sync_id' => 'mykolaiv',
                'street' => 'улица Пивденна',
                'street_ua' => 'вулиця Південна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'a1ba47ff-82ea-e8e9-0179-e6a2404b7353',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Миколаїв',
                'city_sync_id' => 'mykolaiv',
                'street' => 'улица Пивденна',
                'street_ua' => 'вулиця Південна',
                'house_number' => '31А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '2ab6bd80-13d4-fde8-0173-f73a29e30a06',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Суми',
                'city_sync_id' => 'sumy',
                'street' => 'проспект Тараса Шевченко',
                'street_ua' => 'проспект Тараса Шевченка',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'a1ba47ff-82ea-e8e9-0179-e67d7ecd2510',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Суми',
                'city_sync_id' => 'sumy',
                'street' => 'проспект Тараса Шевченко',
                'street_ua' => 'проспект Тараса Шевченка',
                'house_number' => '15А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '77e9b916-6bb3-32aa-0170-808338d1e607',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Івано-Франківськ',
                'city_sync_id' => 'ivano-frankivsk',
                'street' => 'улица Береговая',
                'street_ua' => 'вулиця Берегова',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'a1ba47ff-82ea-e8e9-0179-eabf9ed3d22f',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Івано-Франківськ',
                'city_sync_id' => 'ivano-frankivsk',
                'street' => 'улица Береговая',
                'street_ua' => 'вулиця Берегова',
                'house_number' => '9А',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'de2d1799-0786-3122-0174-832d7233ac11',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Хмельницький',
                'city_sync_id' => 'khmelnytskyi',
                'street' => 'улица Гагарина',
                'street_ua' => 'вулиця Гагаріна',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b5def888-a960-5965-017a-715c5da111ec',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Хмельницький',
                'city_sync_id' => 'khmelnytskyi',
                'street' => 'улица Гагарина',
                'street_ua' => 'вулиця Гагаріна',
                'house_number' => '5',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'eb44ce49-4a6d-9daa-0175-5fee03e2aa18',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Херсон',
                'city_sync_id' => 'kherson',
                'street' => 'улица Рабочая',
                'street_ua' => 'вулиця Робоча',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b5def888-a960-5965-017a-614573bf0a50',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Херсон',
                'city_sync_id' => 'kherson',
                'street' => 'улица Рабочая',
                'street_ua' => 'вулиця Робоча',
                'house_number' => '66',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b1b4473f-086c-2413-0175-cb673a70a118',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Рівне',
                'city_sync_id' => 'rivne',
                'street' => 'проспект Мира',
                'street_ua' => 'проспект Миру',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'a1ba47ff-82ea-e8e9-0179-e5a83658e70f',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Рівне',
                'city_sync_id' => 'rivne',
                'street' => 'проспект Мира',
                'street_ua' => 'проспект Миру',
                'house_number' => '19',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b1b4473f-086c-2413-0175-e5295e611ce8',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Луцьк',
                'city_sync_id' => 'lutsk',
                'street' => 'улица Огиенко',
                'street_ua' => 'вулиця Огієнка',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b5def888-a960-5965-017a-7200ce9cbb93',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Луцьк',
                'city_sync_id' => 'lutsk',
                'street' => 'улица Огиенко',
                'street_ua' => 'вулиця Огієнка',
                'house_number' => '1',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => '0b3e43da-2ca6-e342-0177-24b22c8c6382',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Вінниця',
                'city_sync_id' => 'vinnytsia',
                'street' => 'улица Глеба Успенского',
                'street_ua' => 'вулиця Гліба Успенського',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'a1ba47ff-82ea-e8e9-0179-eb62b3b0c88b',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Вінниця',
                'city_sync_id' => 'vinnytsia',
                'street' => 'улица Глеба Успенского',
                'street_ua' => 'вулиця Гліба Успенського',
                'house_number' => '46',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'c7680804-a673-d33a-0177-6e124f9fe672',
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Тернопіль',
                'city_sync_id' => 'ternopil',
                'street' => 'улица Гоголя',
                'street_ua' => 'вулиця Гоголя',
                'house_number' => '2',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'delivery_terminal_id' => 'b5def888-a960-5965-017a-71945d401aec',
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Тернопіль',
                'city_sync_id' => 'ternopil',
                'street' => 'улица Гоголя',
                'street_ua' => 'вулиця Гоголя',
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('street_ua');
            $table->dropColumn('delivery_terminal_id');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('delivery_terminal_id');
        });
    }
}
