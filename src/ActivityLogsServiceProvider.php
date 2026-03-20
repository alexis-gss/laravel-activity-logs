<?php

namespace LaravelActivityLogs;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelActivityLogs\Console\Commands\InstallActivityLogs;
use LaravelActivityLogs\Database\Seeders\ActivityLogSeeder;
use LaravelActivityLogs\Jobs\CleanOldActivities;
use LaravelActivityLogs\Models\ActivityLog;
use LaravelActivityLogs\Policies\ActivityLogPolicy;
use LaravelFrontend\Enums\SeederCategoryEnum;
use LaravelFrontend\Support\InertiaSharedRegistry;
use LaravelFrontend\Support\SeederRegistry;
use LaravelFrontend\Support\TranslationRegistry;

/**
 * Bootstraps the activity logs package.
 */
class ActivityLogsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallActivityLogs::class,
            ]);
        }
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load resources.
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web-back.php');
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'laravel-activity-logs');

        // Register policies.
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);

        // Add some props to all inertia views.
        InertiaSharedRegistry::add('auth.policies', function () {
            return [
                'activityLogs' => [
                    'viewAny' => Gate::allows('viewAny', ActivityLog::class),
                ],
            ];
        });

        // Add breadcrumbs files to the list.
        $breadcrumbFile = __DIR__ . '/Routes/breadcrumbs-activity-logs.php';
        $files          = config('breadcrumbs.files', []);
        if (!in_array($breadcrumbFile, $files, true)) {
            config([
                'breadcrumbs.files' => [...$files, $breadcrumbFile],
            ]);
        }

        // Add jobs.
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->job(new CleanOldActivities())->daily();
        });

        // Add seeders.
        SeederRegistry::add(SeederCategoryEnum::classic, ActivityLogSeeder::class);

        // Add translations.
        TranslationRegistry::add('back', [
            'laravel-activity-logs::models',
            'laravel-activity-logs::trans',
        ]);
    }
}
