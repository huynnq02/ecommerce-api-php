<?php
// app/Providers/DatabaseServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DatabaseService;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DatabaseService::class, function () {
            return new DatabaseService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
