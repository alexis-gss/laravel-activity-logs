<?php

namespace LaravelActivityLogs;

use Illuminate\Support\ServiceProvider;

class ActivityLogsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        /** Load migrations. */
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }
}
