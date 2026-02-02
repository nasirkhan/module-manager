<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:status {module? : Check specific module status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show status of all modules (package vs published)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $specificModule = $this->argument('module');

        if ($specificModule) {
            return $this->showModuleStatus($specificModule);
        }

        return $this->showAllModulesStatus();
    }

    /**
     * Show status of all modules
     */
    protected function showAllModulesStatus(): int
    {
        $packageModules = $this->getPackageModules();
        $publishedModules = $this->getPublishedModules();
        $allModules = collect($packageModules)->merge($publishedModules)->unique()->sort();

        if ($allModules->isEmpty()) {
            $this->components->warn('No modules found.');

            return self::SUCCESS;
        }

        $rows = [];

        foreach ($allModules as $module) {
            $inPackage = in_array($module, $packageModules);
            $isPublished = in_array($module, $publishedModules);

            if ($isPublished) {
                $location = '<fg=yellow>Modules/ (custom)</>';
                $customized = '<fg=yellow>⚠ Yes</>';
                $updateStrategy = '<fg=gray>User owns this</>';
            } elseif ($inPackage) {
                $location = '<fg=green>vendor (package)</>';
                $customized = '<fg=green>✓ No</>';
                $updateStrategy = '<fg=green>Updateable via composer</>';
            } else {
                $location = '<fg=red>Unknown</>';
                $customized = '<fg=red>?</>';
                $updateStrategy = '<fg=red>Unknown</>';
            }

            $rows[] = [
                $module,
                $location,
                $customized,
                $updateStrategy,
            ];
        }

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>Module Status Overview</>', '');
        $this->table(
            ['Module', 'Location', 'Customized', 'Update Strategy'],
            $rows
        );

        $this->newLine();
        $this->components->info('Commands:');
        $this->line('  <fg=green>php artisan module:publish {module}</> - Publish a module for customization');
        $this->line('  <fg=green>php artisan module:diff {module}</>    - See differences between versions');
        $this->line('  <fg=green>composer update</>                      - Update package modules');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Show status of specific module
     */
    protected function showModuleStatus(string $module): int
    {
        $inPackage = $this->isInPackage($module);
        $isPublished = $this->isPublished($module);

        if (! $inPackage && ! $isPublished) {
            $this->components->error("Module '{$module}' not found.");

            return self::FAILURE;
        }

        $this->newLine();
        $this->components->twoColumnDetail("<fg=bright-blue>Module:</> {$module}", '');
        $this->newLine();

        if ($isPublished) {
            $this->components->twoColumnDetail('Location', '<fg=yellow>Modules/ (custom)</>');
            $this->components->twoColumnDetail('Customized', '<fg=yellow>Yes</>');
            $this->components->twoColumnDetail('Update Strategy', '<fg=gray>User owns this</>');
            $this->newLine();

            if ($inPackage) {
                $this->components->warn('Package version also exists in vendor/');
                $this->line('  Run <fg=green>php artisan module:diff '.$module.'</> to see differences');
            }
        } elseif ($inPackage) {
            $this->components->twoColumnDetail('Location', '<fg=green>vendor (package)</>');
            $this->components->twoColumnDetail('Customized', '<fg=green>No</>');
            $this->components->twoColumnDetail('Update Strategy', '<fg=green>Updateable via composer</>');
            $this->newLine();
            $this->line('  Run <fg=green>php artisan module:publish '.$module.'</> to customize');
        }

        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Get list of package modules
     */
    protected function getPackageModules(): array
    {
        $path = base_path('vendor/nasirkhan/module-manager/src/Modules');

        if (! File::exists($path)) {
            return [];
        }

        return collect(File::directories($path))
            ->map(fn ($dir) => basename($dir))
            ->toArray();
    }

    /**
     * Get list of published modules
     */
    protected function getPublishedModules(): array
    {
        $path = base_path('Modules');

        if (! File::exists($path)) {
            return [];
        }

        return collect(File::directories($path))
            ->map(fn ($dir) => basename($dir))
            ->toArray();
    }

    /**
     * Check if module is in package
     */
    protected function isInPackage(string $module): bool
    {
        return File::exists(base_path("vendor/nasirkhan/module-manager/src/Modules/{$module}"));
    }

    /**
     * Check if module is published
     */
    protected function isPublished(string $module): bool
    {
        return File::exists(base_path("Modules/{$module}"));
    }
}
