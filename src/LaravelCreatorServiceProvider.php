<?php
namespace Programmeruz\LaravelCreator;

use Illuminate\Support\ServiceProvider;

class LaravelCreatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'LaravelCreator');

        // Publish assets
        $this->publishes([
            __DIR__.'/storage/app/templates' => storage_path('app/templates'),
        ], 'laravelcreator-assets');

        // ... any other boot logic
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // ... any register logic
    }
}
