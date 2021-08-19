<?php

namespace App\Services;

use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array $validated
     * @return mixed
     */
    public function store(array $validated)
    {
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role_name']);

        if ($validated['role_name'] === User::ROLE_COURIER) {
            DB::table('courier_iiko')->insert([
                [
                    'restaurant' => Location::SMAKI_MAKI_RESTAURANT,
                    'user_id' => $user->id,
                    'iiko_id' => $validated['iiko_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        return User::with('roles')->findOrFail($user->id);
    }
}
