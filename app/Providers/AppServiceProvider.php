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
        Validator::extend('checkTagType', function ($attribute, $value, $parameters) {
            $tag = Tag::find($value);
            return $tag['type'] == $parameters[0];
        });
        
        Validator::extend('checkParentsAndChildren', function ($attribute, $value, $parameters) {
            if($attribute === 'parents' && $parameters[0] !== 'subcategory' && !empty($value)) {
                return false;
            }
            
            if($attribute === 'children' && $parameters[0] !== 'category' && !empty($value)) {
                return false;
            }
            
            return true;
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
