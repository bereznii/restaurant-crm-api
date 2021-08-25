<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * @param CourierService $courierService
     */
    public function __construct(
        private CourierService $courierService
    ) {}

    /**
     * @param array $validated
     * @return mixed
     */
    public function store(array $validated)
    {
        DB::beginTransaction();

        try {
            $user = $this->createUser($validated);

            $user->assignRole($validated['role_name']);

            if ($validated['role_name'] === User::ROLE_COURIER) {
                $this->courierService->storeIikoData($user->id, $validated['iiko_id']);
            }

            if (isset($validated['locations'])) {
                $this->createRelatedUserLocations($user, $validated);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        /** @var $user User|null */
        return User::with('roles', 'iiko', 'locations')->findOrFail($user->id);
    }

    /**
     * @param int $id
     * @param array $validated
     * @return User|null
     */
    public function update(int $id, array $validated): ?User
    {
        /** @var $user User|null */
        $user = User::with('roles', 'iiko', 'locations')->findOrFail($id);

        DB::beginTransaction();
        try {
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            if (isset($validated['role_name'])) {
                $user->syncRoles([$validated['role_name']]);
            }

            if ($user->roles->contains('name', 'courier') && isset($validated['iiko_id'])) {
                $this->courierService->updateIikoData($user->id, $validated['iiko_id']);
            }

            $user->update($validated);

            if (isset($validated['locations'])) {
                UserLocation::where('user_id', $user->id)->delete();
                $this->createRelatedUserLocations($user, $validated);
                $user->refresh();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return $user;
    }

    /**
     * @param array $attributes
     * @return User
     */
    private function createUser(array $attributes): User
    {
        return User::create([
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'position' => $attributes['position'],
            'phone' => $attributes['phone'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);
    }

    /**
     * @param User $user
     * @param array $validated
     */
    private function createRelatedUserLocations(User $user, array $validated): void
    {
        $user->locationsIds()
            ->createMany(array_map(function ($locationId) {
                return ['location_id' => $locationId];
            }, $validated['locations']));
    }
}
