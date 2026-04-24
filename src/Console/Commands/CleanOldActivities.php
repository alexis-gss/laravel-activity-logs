<?php

namespace LaravelActivityLogs\Console\Commands;

use Illuminate\Console\Command;
use LaravelActivityLogs\Jobs\CleanOldActivities as CleanOldActivitiesJob;

class CleanOldActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alexis-gss:activity-logs:clean {--force : Delete all activity logs regardless of date.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all logs older than one year (use --force to delete all logs).';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $messageTitle = "<comment>activity logs</comment>";
        $this->components->info("Cleaning {$messageTitle}...");

        $force = (bool) $this->option('force');

        if ($force && !$this->confirm('Are you sure you want to delete all activity logs ? This cannot be undone.')) {
            $this->components->error("Clean of {$messageTitle} aborted.");
            return;
        }

        (new CleanOldActivitiesJob(force: $force))->handle();

        $messageContent = $force ? "All {$messageTitle} deleted" : "Old {$messageTitle} cleaned";
        $this->components->success($messageContent);
    }
}
