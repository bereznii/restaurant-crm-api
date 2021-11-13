<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToCourierIikoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_iiko', function (Blueprint $table) {
            $table->string('status')->nullable()->after('iiko_id');
            $table->bigInteger('current_order')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier_iiko', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('current_delivery_id');
        });
    }
}
