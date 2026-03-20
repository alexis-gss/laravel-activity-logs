<?php

namespace LaravelActivityLogs\Console\Commands;

use Illuminate\Console\Command;
use LaravelFrontend\Helpers\PackageHelper;

/**
 * Install activity logs in a Laravel project.
 */
class InstallActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alexis-gss:install-activity-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install activity logs in a Laravel project.';

    /**
     * Hide the command from the artisan list.
     *
     * @var boolean
     */
    protected $hidden = true;

    /**
     * Base path of the package files directory.
     *
     * @var string
     */
    protected string $filesDir;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->filesDir = dirname(__FILE__) . '/../../../files';

        // Define all modifications.
        $steps = [
            'Resources files (directories)' => fn() => PackageHelper::copyDirsFromArray($this->resourcesDirs()),
            'Resources files (files)'       => fn() => PackageHelper::copyFilesFromArray($this->resourcesFiles()),
        ];

        // Show message for each actions.
        collect($steps)->each(function (callable $step, string $label) {
            try {
                $step();
                $this->components->task($label);
            } catch (\Exception $e) {
                $this->components->twoColumnDetail("{$label}", "<fg=red>{$e->getMessage()}</>");
            }
        });
    }

    /**
     * Define resources directories path.
     *
     * @return array<string, string>
     */
    private function resourcesDirs(): array
    {
        return [
            "{$this->filesDir}/resources/ts/back/Pages/ActivityLogs"
                => base_path('./resources/ts/back/Pages/ActivityLogs'),
        ];
    }

    /**
     * Define resources files path.
     *
     * @return array<string, string>
     */
    private function resourcesFiles(): array
    {
        return [
            "{$this->filesDir}/resources/ts/back/Components/Layout/Sidebar/Links/ActivityLogs.tsx"
                => base_path('./resources/ts/back/Components/Layout/Sidebar/Links/ActivityLogs.tsx'),
            "{$this->filesDir}/resources/ts/back/types/activity-logs.d.ts"
                => base_path('./resources/ts/back/types/activity-logs.d.ts'),
        ];
    }
}
