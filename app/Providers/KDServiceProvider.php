<?php

namespace App\Providers;

use App\Helpers\Counter;
use Illuminate\Support\ServiceProvider;

class KDServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    public function register()
    {
        $this->app->singleton('Counter', function () {
            return $this->app->make(Counter::class);
        });
    }

    public function boot()
    {
        /*$this->publishes([
            __DIR__ . '/migrations/' => base_path('/database/migrations')
        ], 'migrations');*/

        /*if (!$this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }*/
    }

    public function provides()
    {
        return ['counter'];
    }
}
