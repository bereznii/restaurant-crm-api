<?php

namespace App\Services;

use App\Http\Resources\Users\UserResource;
use App\Models\iiko\CourierIiko;
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
                    'restaurant' => $validated['restaurant'],
                    'user_id' => $user->id,
                    'iiko_id' => $validated['iiko_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        return User::with('roles')->findOrFail($user->id);
    }

    /**
     * @param int $id
     * @param array $validated
     * @return User|null
     */
    public function update(int $id, array $validated): ?User
    {
        /** @var $user User|null */
        $user = User::with('roles')->findOrFail($id);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        if (isset($validated['role_name'])) {
            $user->syncRoles([$validated['role_name']]);

            if ($validated['role_name'] === User::ROLE_COURIER) {
                CourierIiko::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'restaurant' => $validated['restaurant']
                    ],
                    [
                        'iiko_id' => $validated['iiko_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        $user->update($validated);

        return $user;
    }
}
