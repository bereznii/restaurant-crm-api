<?php

namespace App\Services;

use App\Models\Location;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserProductType;
use Illuminate\Support\Facades\Auth;
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

            if ($validated['role_name'] === User::ROLE_COOK) {
                $this->storeCookTypes($user, $validated);
            }

            $this->createRelatedUserLocations($user, $validated);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(Auth::id() . ' | ' . $e->getMessage());
            throw new \RuntimeException($e->getMessage());
        }

        /** @var $user User|null */
        return User::with('roles', 'iiko', 'locations', 'kitchen')->findOrFail($user->id);
    }

    /**
     * @param int $id
     * @param array $validated
     * @return User|null
     */
    public function update(int $id, array $validated): ?User
    {
        /** @var $user User|null */
        $user = User::with('roles', 'iiko', 'locations', 'kitchen')->findOrFail($id);

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

            if ($user->roles->contains('name', 'cook')) {
                UserProductType::where('user_id', $user->id)->delete();
                $this->storeCookTypes($user, $validated);
                $user->refresh();
            }

            $user->update($validated);

            if (isset($validated['kitchen_code'])) {
                UserLocation::where('user_id', $user->id)->delete();
                $this->createRelatedUserLocations($user, $validated);
                $user->refresh();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(Auth::id() . ' | ' . $e->getMessage());
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
            'position' => $attributes['position'] ?? null,
            'phone' => $attributes['phone'],
            'kitchen_code' => $attributes['kitchen_code'],
            'status' => $attributes['status'] ?? User::STATUS_DISABLED,
            'email' => $attributes['email'] ?? null,
            'password' => Hash::make($attributes['password']),
        ]);
    }

    /**
     * @param User $user
     * @param array $validated
     */
    private function createRelatedUserLocations(User $user, array $validated): void
    {
        $locations = Location::where('kitchen_code', $validated['kitchen_code'])->pluck('id')->toArray();

        $user->locationsIds()
            ->createMany(array_map(function ($locationId) {
                return ['location_id' => $locationId];
            }, $locations));
    }

    /**
     * @param User $user
     * @param array $validated
     */
    private function storeCookTypes(User $user, array $validated): void
    {
        $user->productTypes()
            ->createMany(array_map(function ($type) {
                return ['product_type_sync_id' => $type];
            }, $validated['product_types']));
    }
}
