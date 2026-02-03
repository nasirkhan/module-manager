<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Nasirkhan\ModuleManager\Services\ModuleVersion;

class ModuleDependenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:dependencies {module? : Check specific module dependencies}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check module dependencies and their satisfaction status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $versionService = app(ModuleVersion::class);
        $specificModule = $this->argument('module');

        if ($specificModule) {
            return $this->showModuleDependencies($specificModule, $versionService);
        }

        return $this->showAllDependencies($versionService);
    }

    /**
     * Show dependencies for all modules.
     */
    protected function showAllDependencies(ModuleVersion $versionService): int
    {
        $modules = ['Post', 'Category', 'Tag', 'Menu'];
        $allSatisfied = true;

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>Module Dependencies Overview</>', '');
        $this->newLine();

        foreach ($modules as $module) {
            $moduleData = $versionService->getModuleData($module);
            $dependencies = $moduleData['requires'] ?? [];

            if (empty($dependencies)) {
                $this->components->twoColumnDetail(
                    "<fg=green>{$module}</> v{$moduleData['version']}",
                    '<fg=gray>No dependencies</>'
                );
                continue;
            }

            $depStatus = $versionService->dependenciesSatisfied($module);

            if ($depStatus['all_satisfied']) {
                $this->components->twoColumnDetail(
                    "<fg=green>{$module}</> v{$moduleData['version']}",
                    '<fg=green>✓ All dependencies satisfied</>'
                );

                foreach ($depStatus['satisfied'] as $dep) {
                    $this->line("  └─ {$dep['name']} v{$dep['version']} <fg=green>✓</>");
                }
            } else {
                $allSatisfied = false;
                $this->components->twoColumnDetail(
                    "<fg=red>{$module}</> v{$moduleData['version']}",
                    '<fg=red>✗ Missing dependencies</>'
                );

                foreach ($depStatus['satisfied'] as $dep) {
                    $this->line("  ├─ {$dep['name']} v{$dep['version']} <fg=green>✓</>");
                }

                foreach ($depStatus['missing'] as $dep) {
                    $this->line("  └─ {$dep['name']} <fg=red>✗ MISSING</>");
                }
            }

            $this->newLine();
        }

        if (!$allSatisfied) {
            $this->components->error('Some modules have unsatisfied dependencies!');
            return self::FAILURE;
        }

        $this->components->info('All module dependencies are satisfied.');
        return self::SUCCESS;
    }

    /**
     * Show dependencies for a specific module.
     */
    protected function showModuleDependencies(string $module, ModuleVersion $versionService): int
    {
        $moduleData = $versionService->getModuleData($module);

        if (empty($moduleData)) {
            $this->components->error("Module '{$module}' not found.");
            return self::FAILURE;
        }

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>Module Information</>', '');
        $this->newLine();

        $this->components->twoColumnDetail('Name', $moduleData['name']);
        $this->components->twoColumnDetail('Version', $moduleData['version'] ?? 'unknown');
        $this->components->twoColumnDetail('Description', $moduleData['description'] ?? 'N/A');
        $this->components->twoColumnDetail('Priority', $moduleData['priority'] ?? 0);

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>Dependencies</>', '');
        $this->newLine();

        $dependencies = $moduleData['requires'] ?? [];

        if (empty($dependencies)) {
            $this->components->info('This module has no dependencies.');
            return self::SUCCESS;
        }

        $depStatus = $versionService->dependenciesSatisfied($module);
        $rows = [];

        foreach ($depStatus['satisfied'] as $dep) {
            $rows[] = [
                $dep['name'],
                $dep['version'],
                '<fg=green>✓ Satisfied</>',
            ];
        }

        foreach ($depStatus['missing'] as $dep) {
            $rows[] = [
                $dep['name'],
                '<fg=red>Not installed</>',
                '<fg=red>✗ Missing</>',
            ];
        }

        $this->table(['Dependency', 'Version', 'Status'], $rows);

        if ($depStatus['all_satisfied']) {
            $this->newLine();
            $this->components->info('All dependencies are satisfied.');
            return self::SUCCESS;
        } else {
            $this->newLine();
            $this->components->error('Some dependencies are missing!');
            return self::FAILURE;
        }
    }
}
