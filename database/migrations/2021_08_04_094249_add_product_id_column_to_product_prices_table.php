<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdColumnToProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->bigInteger('product_id')->after('id');
            $table->dropColumn('article');

            $table->unique(['product_id', 'city_sync_id'], 'unique_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->string('article')->after('city_sync_id');

            $table->dropUnique('unique_price');
        });
    }
}
