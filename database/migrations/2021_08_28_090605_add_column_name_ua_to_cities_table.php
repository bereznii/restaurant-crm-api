<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnNameUaToCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('name_ua')->nullable()->after('name');
        });

        $cities = [
            'lviv' => "Львів",
            'mykolaiv' => "Миколаїв",
            'sumy' => "Суми",
            'khmelnytskyi' => "Хмельницький",
            'kherson' => "Херсон",
            'rivne' => "Рівне",
            'lutsk' => "Луцьк",
            'vinnytsia' => "Вінниця",
            'ternopil' => "Тернопіль",
            'ivano-frankivsk' => "Івано-Франківськ",
        ];

        $date = date('Y-m-d H:i:s');
        foreach ($cities as $cityId => $city) {
            DB::table('cities')->where([['sync_id', '=', $cityId]])->update([
                'name_ua' => $city,
                'updated_at' => $date,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('name_ua');
        });
    }
}
