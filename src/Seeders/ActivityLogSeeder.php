<?php

namespace LaravelActivityLogs\Seeders;

use Illuminate\Database\Seeder;
use LaravelActivityLogs\Models\ActivityLog;

class ActivityLogSeeder extends Seeder
{
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
