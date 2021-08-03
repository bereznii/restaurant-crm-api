<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant');
            $table->string('city_sync_id');
            $table->string('article');
            $table->string('title_ua');
            $table->string('title_ru');
            $table->boolean('is_active');
            $table->integer('price');
            $table->integer('price_old')->nullable();
            $table->integer('weight')->nullable();
            $table->string('weight_type', 20)->nullable();
            $table->string('type_sync_id', 20)->nullable();
            $table->text('description_ua')->nullable();
            $table->text('description_ru')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
