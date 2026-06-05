<?php

namespace LaravelActivityLogs\Bootstrap;

/**
 * Register package routes and breadcrumbs.
 */
class BreadcrumbBootstrapper
{
    /**
     * Bootstrap the package.
     *
     * @return void
     */
    public function boot(): void
    {
        // Add breadcrumbs files to the list.
        $breadcrumbFile = __DIR__ . '/../Routes/breadcrumbs-activity-logs.php';
        $files          = config('breadcrumbs.files', []);
        if (!in_array($breadcrumbFile, $files, true)) {
            config([
                'breadcrumbs.files' => [...$files, $breadcrumbFile],
            ]);
        }
    }
}
