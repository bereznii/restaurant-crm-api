<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('city_sync_id');
            $table->dropColumn('price_old');

            $table->unique(['restaurant', 'article'], 'restaurant_article');
        });

        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->string('city_sync_id');
            $table->string('article');
            $table->integer('price');
            $table->integer('price_old')->nullable();
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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('price');
            $table->integer('price_old')->nullable();
            $table->string('city_sync_id');

            $table->dropUnique('restaurant_article');
        });

        Schema::dropIfExists('product_prices');
    }
}
