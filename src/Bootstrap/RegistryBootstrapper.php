<?php

namespace LaravelActivityLogs\Bootstrap;

use Illuminate\Support\Facades\Gate;
use LaravelActivityLogs\Database\Seeders\ActivityLogSeeder;
use LaravelActivityLogs\Models\ActivityLog;
use LaravelCommon\Enums\SeederCategoryEnum;
use LaravelCommon\Support\InertiaSharedRegistry;
use LaravelCommon\Support\SeederRegistry;
use LaravelCommon\Support\TranslationRegistry;

/**
 * Boot registries and bind shared Inertia props, seeders and translations.
 */
class RegistryBootstrapper
{
    /**
     * Bootstrap the package.
     *
     * @return void
     */
    public function boot(): void
    {
        // Add some props to all inertia views.
        InertiaSharedRegistry::add('auth.policies', fn() => [
            'activityLogs' => [
                'viewAny' => Gate::allows('viewAny', ActivityLog::class),
            ],
        ]);

        // Add seeders.
        SeederRegistry::add(SeederCategoryEnum::classic, ActivityLogSeeder::class);

        // Add translations.
        TranslationRegistry::add('back', [
            'laravel-activity-logs::models',
            'laravel-activity-logs::trans',
        ]);
    }
}
