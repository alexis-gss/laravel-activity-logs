<?php

namespace LaravelActivityLogs;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use LaravelActivityLogs\Bootstrap\BreadcrumbBootstrapper;
use LaravelActivityLogs\Bootstrap\RegistryBootstrapper;
use LaravelActivityLogs\Console\Commands\CleanOldActivities;
use LaravelActivityLogs\Console\Commands\InstallActivityLogs;
use LaravelActivityLogs\Jobs\CleanOldActivities as CleanOldActivitiesJob;
use LaravelActivityLogs\Models\ActivityLog;
use LaravelActivityLogs\Policies\ActivityLogPolicy;

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
    #[\Override]
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallActivityLogs::class,
                CleanOldActivities::class,
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
        $this->loadResources();
        $this->initPolicies();
        $this->initJobs();

        resolve(BreadcrumbBootstrapper::class)->boot();
        resolve(RegistryBootstrapper::class)->boot();
    }

    /**
     * Load migrations and translations.
     *
     * @return void
     */
    private function loadResources(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web-back.php');
        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'laravel-activity-logs');
    }

    /**
     * Register model policies.
     *
     * @return void
     */
    private function initPolicies(): void
    {
        Gate::policy(ActivityLog::class, ActivityLogPolicy::class);
    }

    /**
     * Schedule recurring background jobs.
     *
     * @return void
     */
    private function initJobs(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->job(new CleanOldActivitiesJob())->daily();
        });
    }
}
