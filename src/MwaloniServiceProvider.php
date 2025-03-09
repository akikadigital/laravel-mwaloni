<?php

namespace Akika\LaravelMwaloni;

use Illuminate\Support\ServiceProvider;

class MwaloniServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mwaloni', function () {
            return new Mwaloni();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/mwaloni.php' => config_path('mwaloni.php')
            ], 'config');

            /// Publish the ncba migrations
            $this->commands([
                Commands\InstallAkikaLaravelMwaloniPackage::class,
            ]);
        }
    }
}
