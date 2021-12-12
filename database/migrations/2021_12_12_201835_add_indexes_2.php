<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_coordinates', function (Blueprint $table) {
            $table->unique('user_id', 'user_coordinates-user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_coordinates', function (Blueprint $table) {
            $table->dropIndex('user_coordinates-user_id');
        });
    }
}
