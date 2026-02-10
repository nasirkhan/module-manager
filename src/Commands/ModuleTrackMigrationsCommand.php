<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Nasirkhan\ModuleManager\Services\MigrationTracker;
use Nasirkhan\ModuleManager\Services\ModuleVersion;

class ModuleTrackMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:track-migrations 
                            {module? : Track specific module migrations}
                            {--force : Force re-track even if already tracked}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track current state of module migrations for update detection';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tracker = app(MigrationTracker::class);
        $versionService = app(ModuleVersion::class);
        $specificModule = $this->argument('module');

        if ($specificModule) {
            return $this->trackModule($specificModule, $tracker, $versionService);
        }

        return $this->trackAllModules($tracker, $versionService);
    }

    /**
     * Track all modules.
     */
    protected function trackAllModules(MigrationTracker $tracker, ModuleVersion $versionService): int
    {
        $modules = ['Post', 'Category', 'Tag', 'Menu'];

        $this->newLine();
        $this->components->info('Tracking migration state for all modules...');
        $this->newLine();

        foreach ($modules as $module) {
            $this->trackModule($module, $tracker, $versionService, false);
        }

        $this->newLine();
        $this->components->info('✓ All modules tracked successfully!');
        $this->components->twoColumnDetail(
            '<fg=gray>Use this command after composer update to detect new migrations</>',
            ''
        );
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Track specific module.
     */
    protected function trackModule(
        string $module,
        MigrationTracker $tracker,
        ModuleVersion $versionService,
        bool $verbose = true
    ): int {
        $version = $versionService->getVersion($module);

        if (! $version) {
            if ($verbose) {
                $this->components->error("Module '{$module}' not found.");
            }

            return self::FAILURE;
        }

        $existingTracking = $tracker->getTrackingInfo($module);

        if ($existingTracking && ! $this->option('force')) {
            if ($verbose) {
                $this->components->warn("Module '{$module}' is already tracked (version {$existingTracking->version}).");
                $this->line('Use --force to re-track.');
            } else {
                $this->components->twoColumnDetail(
                    "{$module} v{$version}",
                    '<fg=gray>Already tracked</>'
                );
            }

            return self::SUCCESS;
        }

        $migrations = $tracker->getModuleMigrationFiles($module);
        $tracker->trackModuleMigrations($module, $version);

        if ($verbose) {
            $this->newLine();
            $this->components->info("✓ Tracked {$module} v{$version}");
            $this->components->twoColumnDetail('Migrations tracked', count($migrations));

            if (count($migrations) > 0 && $this->option('verbose')) {
                $this->newLine();
                foreach ($migrations as $migration) {
                    $this->line("  <fg=gray>→</> {$migration}");
                }
            }

            $this->newLine();
        } else {
            $this->components->twoColumnDetail(
                "{$module} v{$version}",
                '<fg=green>✓ Tracked ('.count($migrations).' migrations)</>'
            );
        }

        return self::SUCCESS;
    }
}
