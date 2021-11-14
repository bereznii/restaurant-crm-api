<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUsersCurrentOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_iiko', function (Blueprint $table) {
            $table->renameColumn('current_order', 'current_delivery_id');
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
            $table->renameColumn('current_delivery_id', 'current_order');
        });
    }
}
