<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanceColumnsToDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('distance');
            $table->integer('delivery_distance')->nullable()->after('status')
                ->comment('Расстояние от кухни до последнего заказа');
            $table->integer('return_distance')->nullable()->after('delivery_distance')
                ->comment('Расстояние от последнего заказа до кухни');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('delivery_distance');
            $table->dropColumn('return_distance');
            $table->decimal('distance')->nullable()->comment('Общее расстояние');
        });
    }
}
