<?php

namespace Nasirkhan\ModuleManager\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class MigrationTracker
{
    protected string $trackingTable = 'module_migrations_tracking';

    /**
     * Track current state of module migrations.
     */
    public function trackModuleMigrations(string $module, string $version): void
    {
        $this->ensureTrackingTableExists();

        $migrations = $this->getModuleMigrationFiles($module);
        $migrationList = json_encode($migrations);

        DB::table($this->trackingTable)->updateOrInsert(
            ['module' => $module],
            [
                'module' => $module,
                'version' => $version,
                'migrations' => $migrationList,
                'last_checked' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Get new migrations since last check.
     */
    public function getNewMigrationsSinceLastCheck(string $module): array
    {
        $this->ensureTrackingTableExists();

        $tracked = DB::table($this->trackingTable)
            ->where('module', $module)
            ->first();

        $currentMigrations = $this->getModuleMigrationFiles($module);

        if (! $tracked) {
            // First time checking, all migrations are "new"
            return $currentMigrations;
        }

        $trackedMigrations = json_decode($tracked->migrations, true) ?? [];

        // Find migrations that exist now but weren't tracked before
        return array_diff($currentMigrations, $trackedMigrations);
    }

    /**
     * Get module migration files.
     */
    public function getModuleMigrationFiles(string $module): array
    {
        // Check both package and published locations
        $paths = [
            base_path("vendor/nasirkhan/module-manager/src/Modules/{$module}/database/migrations"),
            base_path("Modules/{$module}/database/migrations"),
        ];

        $migrations = [];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                $files = File::files($path);
                foreach ($files as $file) {
                    $migrations[] = $file->getFilename();
                }
            }
        }

        return array_unique($migrations);
    }

    /**
     * Get migrations that haven't been run yet.
     */
    public function getPendingMigrations(string $module): array
    {
        $allMigrations = $this->getModuleMigrationFiles($module);

        try {
            $ranMigrations = DB::table('migrations')
                ->pluck('migration')
                ->map(function ($migration) {
                    // Add .php extension if not present
                    return str_ends_with($migration, '.php') ? $migration : $migration.'.php';
                })
                ->toArray();

            return array_filter($allMigrations, function ($migration) use ($ranMigrations) {
                $migrationName = pathinfo($migration, PATHINFO_FILENAME);

                return ! in_array($migrationName, $ranMigrations) && ! in_array($migration, $ranMigrations);
            });
        } catch (\Exception $e) {
            // If migrations table doesn't exist, all migrations are pending
            return $allMigrations;
        }
    }

    /**
     * Get tracking information for a module.
     */
    public function getTrackingInfo(string $module): ?object
    {
        $this->ensureTrackingTableExists();

        return DB::table($this->trackingTable)
            ->where('module', $module)
            ->first();
    }

    /**
     * Get tracking information for all modules.
     */
    public function getAllTrackingInfo(): array
    {
        $this->ensureTrackingTableExists();

        return DB::table($this->trackingTable)
            ->get()
            ->keyBy('module')
            ->toArray();
    }

    /**
     * Compare current state with tracked state.
     */
    public function compareWithTracked(string $module): array
    {
        $tracked = $this->getTrackingInfo($module);
        $current = $this->getModuleMigrationFiles($module);

        if (! $tracked) {
            return [
                'status' => 'not_tracked',
                'new_migrations' => $current,
                'removed_migrations' => [],
                'total_new' => count($current),
                'total_removed' => 0,
            ];
        }

        $trackedMigrations = json_decode($tracked->migrations, true) ?? [];
        $newMigrations = array_diff($current, $trackedMigrations);
        $removedMigrations = array_diff($trackedMigrations, $current);

        return [
            'status' => 'tracked',
            'tracked_version' => $tracked->version,
            'last_checked' => $tracked->last_checked,
            'new_migrations' => array_values($newMigrations),
            'removed_migrations' => array_values($removedMigrations),
            'total_new' => count($newMigrations),
            'total_removed' => count($removedMigrations),
        ];
    }

    /**
     * Ensure tracking table exists.
     */
    protected function ensureTrackingTableExists(): void
    {
        if (! Schema::hasTable($this->trackingTable)) {
            Schema::create($this->trackingTable, function ($table) {
                $table->id();
                $table->string('module')->unique();
                $table->string('version');
                $table->json('migrations');
                $table->timestamp('last_checked');
                $table->timestamps();
            });
        }
    }

    /**
     * Update tracking after package update.
     */
    public function updateAfterComposerUpdate(): void
    {
        $versionService = app(ModuleVersion::class);
        $modules = ['Post', 'Category', 'Tag', 'Menu'];

        foreach ($modules as $module) {
            $version = $versionService->getVersion($module);
            if ($version) {
                $this->trackModuleMigrations($module, $version);
            }
        }
    }

    /**
     * Check if module has updates.
     */
    public function hasUpdates(string $module): bool
    {
        $comparison = $this->compareWithTracked($module);

        return $comparison['total_new'] > 0 || $comparison['total_removed'] > 0;
    }
}
