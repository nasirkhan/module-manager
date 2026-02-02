<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModulePublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:publish {module : The name of the module to publish}
                            {--force : Overwrite existing published module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a module from vendor to Modules directory for customization';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $moduleName = $this->argument('module');
        $sourcePath = base_path("vendor/nasirkhan/module-manager/src/Modules/{$moduleName}");
        $destinationPath = base_path("Modules/{$moduleName}");

        // Check if module exists in package
        if (! File::exists($sourcePath)) {
            $this->error("Module '{$moduleName}' not found in package.");
            $this->line('Available modules:');
            $this->listAvailableModules();

            return self::FAILURE;
        }

        // Check if already published
        if (File::exists($destinationPath) && ! $this->option('force')) {
            if (! $this->confirm("Module '{$moduleName}' already exists in Modules/. Overwrite?")) {
                $this->info('Publishing cancelled.');

                return self::SUCCESS;
            }
        }

        // Remove existing if --force or confirmed
        if (File::exists($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }

        // Copy module
        File::copyDirectory($sourcePath, $destinationPath);

        // Update module status
        $this->updateModuleStatus($moduleName, true);

        // Success message
        $this->newLine();
        $this->components->info("Module '{$moduleName}' published successfully!");
        $this->line("Location: <fg=gray>{$destinationPath}</>");
        $this->newLine();
        $this->components->warn('Note: This module is now user-owned and won\'t be updated via composer.');
        $this->line('  - You have full control to customize it');
        $this->line('  - Use <fg=green>php artisan module:diff {$moduleName}</> to see package updates');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Update module status in modules_statuses.json.
     */
    protected function updateModuleStatus(string $module, bool $published): void
    {
        $statusFile = base_path('modules_statuses.json');
        $statuses = File::exists($statusFile)
            ? json_decode(File::get($statusFile), true)
            : [];

        $statuses[$module] = [
            'published' => $published,
            'published_at' => now()->toISOString(),
            'location' => 'user',
            'version' => $this->getModuleVersion($module),
        ];

        File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get module version from composer.json or service provider.
     */
    protected function getModuleVersion(string $module): string
    {
        // Try to get from module's composer.json if exists
        $composerFile = base_path("Modules/{$module}/composer.json");
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);

            return $composer['version'] ?? '1.0.0';
        }

        // Default version
        return '1.0.0';
    }

    /**
     * List available modules in package.
     */
    protected function listAvailableModules(): void
    {
        $modulesPath = base_path('vendor/nasirkhan/module-manager/src/Modules');

        if (! File::exists($modulesPath)) {
            return;
        }

        $modules = collect(File::directories($modulesPath))
            ->map(fn ($dir) => basename($dir))
            ->values();

        if ($modules->isEmpty()) {
            $this->line('  No modules available in package.');

            return;
        }

        foreach ($modules as $module) {
            $this->line("  - {$module}");
        }
    }
}
