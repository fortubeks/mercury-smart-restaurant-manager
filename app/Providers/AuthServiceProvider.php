<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('restaurant', function () {
            return auth()->user()->restaurant;
        });

        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });
    }
}
