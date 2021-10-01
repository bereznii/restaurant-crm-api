<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class Add1cUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::create([
            'first_name' => 'Синхронизация товаров с 1С',
            'last_name' => '',
            'phone' => env('PRODUCTS_SYNC_LOGIN'),
            'password' => Hash::make(env('PRODUCTS_SYNC_PASSWORD')),
        ]);

        $user->syncRoles(['external_service']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('phone', '=', env('PRODUCTS_SYNC_LOGIN'))->delete();
    }
}
