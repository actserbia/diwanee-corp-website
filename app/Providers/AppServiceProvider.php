<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Tag;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Validator::extend('checkTagType', function ($attribute, $value, $parameters, $validator) {
            $tag = Tag::find($value);
            return $tag['type'] == $parameters[0];
        });
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
