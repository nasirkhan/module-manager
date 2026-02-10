<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Nasirkhan\ModuleManager\Services\MigrationTracker;
use Nasirkhan\ModuleManager\Services\ModuleVersion;

class ModuleDetectUpdatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:detect-updates 
                            {module? : Check specific module for updates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect new migrations and updates from module packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tracker = app(MigrationTracker::class);
        $versionService = app(ModuleVersion::class);
        $specificModule = $this->argument('module');

        if ($specificModule) {
            return $this->detectModuleUpdates($specificModule, $tracker, $versionService);
        }

        return $this->detectAllUpdates($tracker, $versionService);
    }

    /**
     * Detect updates for all modules.
     */
    protected function detectAllUpdates(MigrationTracker $tracker, ModuleVersion $versionService): int
    {
        $modules = ['Post', 'Category', 'Tag', 'Menu'];
        $hasUpdates = false;

        $this->newLine();
        $this->components->info('Checking for module updates and new migrations...');
        $this->newLine();

        foreach ($modules as $module) {
            $comparison = $tracker->compareWithTracked($module);
            $version = $versionService->getVersion($module);

            if ($comparison['status'] === 'not_tracked') {
                $this->components->warn("{$module} v{$version} - Not yet tracked");
                $this->line('  <fg=gray>Run: php artisan module:track-migrations</>');
                $this->newLine();
                continue;
            }

            $versionChanged = $comparison['tracked_version'] !== $version;
            $hasNewMigrations = $comparison['total_new'] > 0;

            if ($versionChanged || $hasNewMigrations) {
                $hasUpdates = true;

                $status = '';
                if ($versionChanged) {
                    $status .= "<fg=yellow>Version: {$comparison['tracked_version']} → {$version}</> ";
                }
                if ($hasNewMigrations) {
                    $status .= "<fg=bright-blue>{$comparison['total_new']} new migration(s)</>";
                }

                $this->components->twoColumnDetail(
                    "<fg=green>{$module}</>",
                    $status
                );

                if ($hasNewMigrations) {
                    foreach ($comparison['new_migrations'] as $migration) {
                        $this->line("  <fg=gray>→</> {$migration}");
                    }
                }

                if ($comparison['total_removed'] > 0) {
                    $this->line("  <fg=red>⚠ {$comparison['total_removed']} migration(s) removed</>");
                }

                $this->newLine();
            } else {
                $this->components->twoColumnDetail(
                    "{$module} v{$version}",
                    '<fg=gray>✓ No updates</>'
                );
            }
        }

        if (! $hasUpdates) {
            $this->newLine();
            $this->components->info('✓ All modules are up to date!');
            $this->newLine();

            return self::SUCCESS;
        }

        $this->components->info('To update tracking state:');
        $this->line('  <fg=green>php artisan module:track-migrations --force</>');
        $this->newLine();

        $this->components->info('To publish new migrations:');
        $this->line('  <fg=green>php artisan vendor:publish --tag=post-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=category-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=tag-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=menu-migrations</>');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Detect updates for specific module.
     */
    protected function detectModuleUpdates(
        string $module,
        MigrationTracker $tracker,
        ModuleVersion $versionService
    ): int {
        $comparison = $tracker->compareWithTracked($module);
        $currentVersion = $versionService->getVersion($module);

        if ($comparison['status'] === 'not_tracked') {
            $this->components->warn("Module '{$module}' is not yet tracked.");
            $this->line('Run: <fg=green>php artisan module:track-migrations {$module}</>');

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>Module</>', $module);
        $this->components->twoColumnDetail('<fg=bright-blue>Current Version</>', $currentVersion);
        $this->components->twoColumnDetail('<fg=bright-blue>Tracked Version</>', $comparison['tracked_version']);
        $this->components->twoColumnDetail('<fg=bright-blue>Last Checked</>', $comparison['last_checked']);
        $this->newLine();

        $versionChanged = $comparison['tracked_version'] !== $currentVersion;

        if ($versionChanged) {
            $this->components->warn("⚠ Version changed: {$comparison['tracked_version']} → {$currentVersion}");
            $this->newLine();
        }

        if ($comparison['total_new'] > 0) {
            $this->components->info("New Migrations ({$comparison['total_new']}):");
            foreach ($comparison['new_migrations'] as $migration) {
                $this->line("  <fg=green>+</> {$migration}");
            }
            $this->newLine();
        }

        if ($comparison['total_removed'] > 0) {
            $this->components->warn("Removed Migrations ({$comparison['total_removed']}):");
            foreach ($comparison['removed_migrations'] as $migration) {
                $this->line("  <fg=red>-</> {$migration}");
            }
            $this->newLine();
        }

        if ($comparison['total_new'] === 0 && $comparison['total_removed'] === 0 && ! $versionChanged) {
            $this->components->info('✓ No updates detected for this module.');
            $this->newLine();

            return self::SUCCESS;
        }

        $this->components->info('Next steps:');
        if ($comparison['total_new'] > 0) {
            $tag = strtolower($module).'-migrations';
            $this->line("  <fg=green>php artisan vendor:publish --tag={$tag}</>");
        }
        $this->line("  <fg=green>php artisan module:track-migrations {$module} --force</>");
        $this->newLine();

        return self::SUCCESS;
    }
}
