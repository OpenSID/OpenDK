<?php

namespace App\Providers;

use App\Classes\MenuNav\MenuNav;
use Illuminate\Support\ServiceProvider;

class MenuNavServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Menu', function () {
            return new MenuNav();
        });
    }
}
