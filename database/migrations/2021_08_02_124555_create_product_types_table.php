<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('sync_id');
            $table->string('name');
            $table->timestamps();
        });

        $date = date('Y-m-d H:i:s');
        DB::table('product_types')->insert([
            [
                'sync_id' => 'pizza',
                'name' => 'Пицца',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'sushi',
                'name' => 'Суши',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'soup',
                'name' => 'Мисо-супы',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'sync_id' => 'other',
                'name' => 'Прочее',
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
        Schema::dropIfExists('product_types');
    }
}
