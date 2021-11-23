<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCallcenterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('courier_id')->nullable()->change();
        });

        Schema::table('order_address', function (Blueprint $table) {
            $table->string('apartment')->nullable()->after('floor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('courier_id');
        });

        Schema::table('order_address', function (Blueprint $table) {
            $table->dropColumn('apartment');
        });
    }
}
