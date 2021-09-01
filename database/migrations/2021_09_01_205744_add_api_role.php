<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddApiRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::create(['title' => 'Внешний сервис', 'name' => 'external_service']);
        $permission = Permission::create(['title' => 'Доступ к системному API', 'name' => 'api_access']);
        $permission->assignRole($role);

        $user = User::create([
            'first_name' => 'Мобильное приложение',
            'last_name' => '',
            'position' => 'Внешний сервис',
            'phone' => env('MOBILE_APP_LOGIN'),
            'email' => 'mobileapp@mobileapp.mobileapp',
            'password' => Hash::make(env('MOBILE_APP_PASSWORD')),
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
        Role::findByName('external_service')->delete();
        Permission::findByName('api_access')->delete();

        User::where('email', '=', 'mobileapp@mobileapp.mobileapp')->delete();
    }
}
