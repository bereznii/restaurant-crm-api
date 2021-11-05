<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddZhytomyrKitchen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $date = date('Y-m-d H:i:s');

        DB::table('cities')->insert([
            [
                'sync_id' => 'zhytomyr',
                'uuid' => 'ac4fd032-2170-48a7-8547-f045d17db5f1',
                'name' => 'Житомир',
                'name_ua' => 'Житомир',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);

        DB::table('kitchens')->insert([
            [
                'code' => 'zhytomyr',
                'title' => 'Житомир',
                'created_at' => $date,
                'updated_at' => $date,
            ]
        ]);

        DB::table('locations')->insert([
            [
                'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                'name' => 'Smaki Maki Житомир',
                'delivery_terminal_id' => '8130a5f4-7d3a-94d3-017c-035f651808ba',
                'city_sync_id' => 'zhytomyr',
                'street' => 'улица Леха Качинского',
                'street_ua' => 'вулиця Леха Качинського',
                'kitchen_code' => 'zhytomyr',
                'house_number' => '6',
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'restaurant' => Location::SUSHI_GO_RESTAURANT,
                'name' => 'Sushi Go Житомир',
                'delivery_terminal_id' => '85b7a355-74c1-76dc-017b-f36f052a406e',
                'city_sync_id' => 'zhytomyr',
                'street' => 'улица Леха Качинского',
                'street_ua' => 'вулиця Леха Качинського',
                'kitchen_code' => 'zhytomyr',
                'house_number' => '6',
                'created_at' => $date,
                'updated_at' => $date,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Location::where('city_sync_id', '=', 'zhytomyr')->delete();
        \App\Models\City::where('sync_id', '=', 'zhytomyr')->delete();
        \App\Models\Kitchen::where('code', '=', 'zhytomyr')->delete();
    }
}
