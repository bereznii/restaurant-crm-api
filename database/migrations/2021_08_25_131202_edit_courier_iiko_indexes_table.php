<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCourierIikoIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_iiko', function (Blueprint $table) {
            $table->dropUnique('unique_iiko_user');
            $table->unique(['iiko_id'], 'unique_iiko_uuid');
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
            $table->dropUnique('unique_iiko_uuid');
            $table->unique(['iiko_id', 'user_id'], 'unique_iiko_user');
        });
    }
}
