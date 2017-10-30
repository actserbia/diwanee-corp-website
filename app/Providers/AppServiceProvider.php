<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use App\Validation\Rules;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Rules::addRules();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        SessionGuard::macro('admin', function () {
            return (Auth::check() && Auth::user()->role === 'admin') ? true : false;
        });
        SessionGuard::macro('editor', function () {
            return (Auth::check() && Auth::user()->role === 'moderator') ? true : false;
        });
    }
}
