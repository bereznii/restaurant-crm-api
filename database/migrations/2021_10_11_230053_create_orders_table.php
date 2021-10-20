<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant');
            $table->string('city_sync_id');
            $table->string('kitchen_code');
            $table->string('payment_type');
            $table->string('type')->comment('Алгоритм по работе с заказами');
            $table->string('status')->comment('Текущий статус');
            $table->boolean('return_call');
            $table->bigInteger('client_id');
            $table->bigInteger('courier_id')->nullable();
            $table->bigInteger('operator_id')->nullable()->comment('Пользователь');
            $table->text('client_comment')->nullable()->comment('Комментарий от клиента');
            $table->text('comment_for_cook')->nullable()->comment('Внутренний комментарий для повара');
            $table->text('comment_for_courier')->nullable()->comment('Внутренний комментарий для курьера');
            $table->timestamp('delivered_till')->nullable()->comment('Требуемое время для доставки');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->uuid('product_id');
            $table->tinyInteger('quantity');
            $table->integer('sum')->comment('Стоимость');
            $table->text('comment');
            $table->timestamps();

            $table->index(['order_id']);
        });

        // Повара на которых назначен заказ
        Schema::create('order_cooks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id')->comment('ID повара на которого назначен заказ');
            $table->timestamps();

            $table->index(['order_id']);
        });

        // История изменений статуса заказа
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('user_id');
            $table->string('status');
            $table->text('comment')->nullable();
            $table->timestamp('set_at');

            $table->index(['order_id']);
        });

        Schema::create('order_address', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->string('city_sync_id');
            $table->string('street');
            $table->string('house_number')->nullable()->comment('Номер дома');
            $table->string('entrance')->nullable()->comment('Подъезд');
            $table->string('floor')->nullable()->comment('Этаж');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['order_id']);
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('phone')->unique();
            $table->string('source');
            $table->boolean('is_regular')->default(0);
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_cooks');
        Schema::dropIfExists('order_statuses');
        Schema::dropIfExists('order_address');
        Schema::dropIfExists('clients');
    }
}
