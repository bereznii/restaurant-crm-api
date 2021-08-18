<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierIikoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_iiko', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant');
            $table->bigInteger('user_id');
            $table->uuid('iiko_id');
            $table->timestamps();

            $table->unique(['iiko_id', 'user_id'], 'unique_iiko_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courier_iiko');
    }
}
