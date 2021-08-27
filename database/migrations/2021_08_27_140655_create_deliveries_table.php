<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->uuid('iiko_courier_id');
            $table->bigInteger('user_id');
            $table->string('status')->default('on_way');
            $table->decimal('distance')->nullable()->comment('Общее расстояние');
            $table->timestamp('started_at')->nullable()->comment('Начало поездки');
            $table->timestamps();
        });

        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_id');
            $table->string('restaurant');
            $table->uuid('iiko_order_id');
            $table->string('status')->default('on_way');
            $table->uuid('range_type')->comment('В пределах ли города'); //within_city,outside_city
            $table->timestamp('delivered_at')->nullable()->comment('Время доставки заказа клиенту');
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
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('delivery_orders');
    }
}
