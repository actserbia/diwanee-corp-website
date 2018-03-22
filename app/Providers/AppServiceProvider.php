<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Validation\Rules;
use App\Search\NodesRepository;
use App\Search\EloquentNodesRepository;
use App\Search\ElasticNodesRepository;
use Elasticsearch\Client;


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
        Schema::defaultStringLength(191);
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

        $this->app->bind(NodesRepository::class, function () {
            return new EloquentNodesRepository();
        });

        $this->app->singleton(NodesRepository::class, function($app) {
            // This is useful in case we want to turn-off our
            // search cluster or when deploying the search
            // to a live, running application at first.
            if (! env('ELASTICSEARCH_ENABLED', false)) {
                return new EloquentNodesRepository();
            }
            return new ElasticNodesRepository(
                $app->make(Client::class)
            );
        });
    }
}
