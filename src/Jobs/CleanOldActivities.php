<?php

namespace LaravelActivityLogs\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelActivityLogs\Models\ActivityLog;

/**
 * Permanently deletes entries that have been stored for more than 3 months.
 */
class CleanOldActivities implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Delete entries if it has been stored for more than 3 months.
     *
     * @return void
     */
    public function handle(): void
    {
        $dateLimit = Carbon::now()->subMonths(3);

        ActivityLog::query()
            ->where('created_at', '<', $dateLimit)
            ->cursor()
            ->each(fn($model) => $model->delete());
    }
}
