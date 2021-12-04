<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('courier_iiko', function (Blueprint $table) {
                $table->index('user_id', 'courier_iiko-user_id');
            });
        } catch (\Illuminate\Database\QueryException) {}

        try {
            Schema::table('delivery_orders', function (Blueprint $table) {
                $table->index(['delivery_id', 'status', 'iiko_order_id'], 'delivery_orders-delivery_id-status-iiko_order_id');
            });
        } catch (\Illuminate\Database\QueryException) {}

        try {
            Schema::table('user_coordinates', function (Blueprint $table) {
                $table->unique('user_id', 'user_coordinates-user_id');
            });
        } catch (\Illuminate\Database\QueryException) {}

        try {
            Schema::table('locations', function (Blueprint $table) {
                $table->unique('delivery_terminal_id', 'locations-delivery_terminal_id');
            });
        } catch (\Illuminate\Database\QueryException) {}

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('phone', 'users-phone');
                $table->index('kitchen_code', 'users-kitchen_code');
            });
        } catch (\Illuminate\Database\QueryException) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier_iiko', function (Blueprint $table) {
            $table->dropIndex('courier_iiko-user_id');
        });

        Schema::table('delivery_orders', function (Blueprint $table) {
            $table->dropIndex('delivery_orders-delivery_id-status-iiko_order_id');
        });

        Schema::table('user_coordinates', function (Blueprint $table) {
            $table->dropIndex('user_coordinates-user_id');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('locations-delivery_terminal_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users-phone');
            $table->dropIndex('users-kitchen_code');
        });
    }
}
