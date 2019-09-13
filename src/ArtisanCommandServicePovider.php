<?php

namespace App\Providers;

use Davinet\ArtisanCommand\Commands\Repository;
use Davinet\ArtisanCommand\Commands\View;
use Illuminate\Support\ServiceProvider;

class ArtisanCommandServicePovider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Repository::class,
                View::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
