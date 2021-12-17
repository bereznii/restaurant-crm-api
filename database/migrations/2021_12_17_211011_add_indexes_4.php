<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('restaurant', 'products-restaurant');
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unique('sync_id', 'product_categories-sync_id');
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->unique('sync_id', 'product_types-sync_id');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->index('kitchen_code', 'locations-kitchen_code');
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
            $table->dropIndex('products_restaurant');
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex('product_categories-sync_id');
        });
        Schema::table('product_types', function (Blueprint $table) {
            $table->dropIndex('product_types-sync_id');
        });
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations-kitchen_code');
        });
    }
}
