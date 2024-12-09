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
        /** Publish all files. */
        $this->publishes([
            __DIR__ . '/Migrations/2024_12_10_100000_create_actitivy_logs_table.php'
                => base_path('database/migrations/2024_12_10_100000_create_actitivy_logs_table.php'),
        ]);
    }
}
