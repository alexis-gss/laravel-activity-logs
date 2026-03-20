<?php

namespace LaravelActivityLogs\Database\Seeders;

use Illuminate\Database\Seeder;
use LaravelActivityLogs\Models\ActivityLog;

/**
 * Seeds the activity_logs table.
 */
class ActivityLogSeeder extends Seeder
{
    /** @var string $table Associated table to this seeder. */
    public static string $table = 'activity_logs';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        ActivityLog::factory(10)->createQuietly();
    }
}
