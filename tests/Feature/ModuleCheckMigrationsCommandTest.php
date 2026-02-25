<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleCheckMigrationsCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('Modules/FakeModule'));
        File::deleteDirectory(base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule'));

        parent::tearDown();
    }

    #[Test]
    public function it_reports_up_to_date_when_no_migration_paths_exist(): void
    {
        // No Modules/ or vendor path present — all modules return empty.
        $this->artisan('module:check-migrations')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_reports_up_to_date_for_a_specific_module_with_no_migrations(): void
    {
        $this->artisan('module:check-migrations', ['module' => 'Tag'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_detects_new_migrations_from_the_local_published_path(): void
    {
        $migrationsPath = base_path('Modules/FakeModule/Database/Migrations');
        File::ensureDirectoryExists($migrationsPath);
        File::put("{$migrationsPath}/2026_01_01_000000_create_fake_table.php", "<?php // migration\n");

        // The migrations table does not exist in :memory: SQLite at this point,
        // so all migration files will be treated as new.
        $this->artisan('module:check-migrations', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_falls_back_to_vendor_path_when_no_local_path_exists(): void
    {
        // Only vendor path — no published Modules/ directory.
        $vendorMigrationsPath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule/Database/Migrations');
        File::ensureDirectoryExists($vendorMigrationsPath);
        File::put("{$vendorMigrationsPath}/2026_01_01_000001_create_fake_vendor_table.php", "<?php // migration\n");

        $this->artisan('module:check-migrations', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_prefers_local_path_over_vendor_path_when_both_exist(): void
    {
        // Both paths exist — local should take precedence.
        $localPath = base_path('Modules/FakeModule/Database/Migrations');
        $vendorPath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule/Database/Migrations');

        File::ensureDirectoryExists($localPath);
        File::ensureDirectoryExists($vendorPath);
        File::put("{$localPath}/2026_01_01_000000_local.php", "<?php // local\n");
        File::put("{$vendorPath}/2026_01_01_000001_vendor.php", "<?php // vendor\n");

        // The vendor-only migration should NOT appear because local path takes priority.
        $this->artisan('module:check-migrations', ['module' => 'FakeModule'])
            ->doesntExpectOutput('2026_01_01_000001_vendor.php')
            ->assertExitCode(0);
    }
}
