<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('order_cooks');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('comment_for_cook');
            $table->dropColumn('comment_for_courier');
            $table->dropColumn('city_sync_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->bigInteger('cook_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Повара на которых назначен заказ
        Schema::create('order_cooks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id')->comment('ID повара на которого назначен заказ');
            $table->timestamps();

            $table->index(['order_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('comment_for_cook')->nullable()->comment('Внутренний комментарий для повара');
            $table->text('comment_for_courier')->nullable()->comment('Внутренний комментарий для курьера');
            $table->string('city_sync_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('cook_id');
        });
    }
}
