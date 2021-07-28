<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('sync_id')->unique()->comment('ID для синхронизации');
            $table->string('name');
            $table->timestamps();
        });

        $date = date('Y-m-d H:i:s');
        DB::table('cities')->insert([
            [
                'sync_id' => 'lviv',
                'name' => 'Львов',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'mykolaiv',
                'name' => 'Николаев',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'sumy',
                'name' => 'Сумы',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'ivano-frankivsk',
                'name' => 'Ивано-Франковск',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'khmelnytskyi',
                'name' => 'Хмельницький',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => 'kherson',
                'name' => 'Херсон',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => 'rivne',
                'name' => 'Ровно',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => 'lutsk',
                'name' => 'Луцк',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => 'vinnytsia',
                'name' => 'Винница',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => 'ternopil',
                'name' => 'Тернополь',
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
        Schema::dropIfExists('cities');
    }
}
