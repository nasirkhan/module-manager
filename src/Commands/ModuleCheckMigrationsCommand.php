<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModuleCheckMigrationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:check-migrations {module? : Check specific module migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new migrations from module packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $specificModule = $this->argument('module');

        if ($specificModule) {
            return $this->checkModuleMigrations($specificModule);
        }

        return $this->checkAllModuleMigrations();
    }

    /**
     * Check all module migrations
     */
    protected function checkAllModuleMigrations(): int
    {
        $modules = ['Post', 'Category', 'Tag', 'Menu'];
        $hasNewMigrations = false;

        $this->newLine();
        $this->components->info('Checking for new migrations from module packages...');
        $this->newLine();

        foreach ($modules as $module) {
            $newMigrations = $this->getNewMigrations($module);

            if (! empty($newMigrations)) {
                $hasNewMigrations = true;
                $this->displayModuleMigrations($module, $newMigrations);
            }
        }

        if (! $hasNewMigrations) {
            $this->components->info('✓ No new migrations found. All modules are up to date!');
            $this->newLine();

            return self::SUCCESS;
        }

        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>To publish migrations:</>', '');
        $this->line('  <fg=green>php artisan vendor:publish --tag=post-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=category-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=tag-migrations</>');
        $this->line('  <fg=green>php artisan vendor:publish --tag=menu-migrations</>');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Check migrations for a specific module
     */
    protected function checkModuleMigrations(string $module): int
    {
        $newMigrations = $this->getNewMigrations($module);

        if (empty($newMigrations)) {
            $this->components->info("✓ No new migrations for {$module} module.");

            return self::SUCCESS;
        }

        $this->displayModuleMigrations($module, $newMigrations);
        $this->newLine();
        $this->components->twoColumnDetail('<fg=bright-blue>To publish:</>', '');
        $tag = strtolower($module).'-migrations';
        $this->line("  <fg=green>php artisan vendor:publish --tag={$tag}</>");
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Get new migrations for a module
     */
    protected function getNewMigrations(string $module): array
    {
        // Check package migrations path
        $packagePath = base_path("Modules/{$module}/Database/Migrations");

        if (! File::exists($packagePath)) {
            return [];
        }

        // Get all migration files from package
        $packageMigrations = File::files($packagePath);
        $newMigrations = [];

        try {
            // Get already run migrations
            $ranMigrations = DB::table('migrations')->pluck('migration')->toArray();

            foreach ($packageMigrations as $file) {
                $migrationName = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                // Check if migration hasn't been run
                if (! in_array($migrationName, $ranMigrations)) {
                    $newMigrations[] = $file->getFilename();
                }
            }
        } catch (\Exception $e) {
            // If migrations table doesn't exist, assume all are new
            foreach ($packageMigrations as $file) {
                $newMigrations[] = $file->getFilename();
            }
        }

        return $newMigrations;
    }

    /**
     * Display module migrations
     */
    protected function displayModuleMigrations(string $module, array $migrations): void
    {
        $this->components->twoColumnDetail(
            "<fg=yellow>{$module} Module</>",
            '<fg=bright-blue>'.count($migrations).' new migration(s)</>'
        );

        foreach ($migrations as $migration) {
            $this->line("  <fg=gray>→</> {$migration}");
        }

        $this->newLine();
    }
}
