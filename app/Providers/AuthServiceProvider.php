<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** @link https://spatie.be/docs/laravel-permission/v4/basic-usage/super-admin#gatebefore */
        Gate::before(function ($user, $ability) {
            return $user->hasRole('system_administrator') ? true : null;
        });

        /** @link https://laravel.com/docs/8.x/passport#installation */
        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
