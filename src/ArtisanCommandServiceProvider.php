<?php

namespace Davinet\ArtisanCommand;

use Davinet\ArtisanCommand\Commands\File;
use Davinet\ArtisanCommand\Commands\Lang;
use Davinet\ArtisanCommand\Commands\Repository;
use Davinet\ArtisanCommand\Commands\View;
use Illuminate\Support\ServiceProvider;

class ArtisanCommandServiceProvider extends ServiceProvider
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
                View::class,
                File::class,
                Lang::class,
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
