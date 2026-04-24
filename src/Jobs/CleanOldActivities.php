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
 * Permanently deletes entries that have been stored for more than one year.
 */
class CleanOldActivities implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create the controller instance.
     *
     * @param boolean $force
     */
    public function __construct(public readonly bool $force = false)
    {
    }

    /**
     * Delete entries if it has been stored for more than one year.
     *
     * @return void
     */
    public function handle(): void
    {
        $query = ActivityLog::query();

        if (!$this->force) {
            $query->where('created_at', '<', Carbon::now()->subYear());
        }

        $query->cursor()->each(fn($model) => $model->delete());
    }
}
