<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Services\MigrationTracker;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MigrationTrackerTest extends TestCase
{
    private string $vendorMigrationsPath;

    private string $publishedMigrationsPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendorMigrationsPath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule/database/migrations');
        $this->publishedMigrationsPath = base_path('Modules/FakeModule/database/migrations');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule'));
        File::deleteDirectory(base_path('Modules/FakeModule'));

        parent::tearDown();
    }

    private function createMigrationFile(string $dir, string $filename): void
    {
        File::ensureDirectoryExists($dir);
        File::put("{$dir}/{$filename}", '<?php // migration');
    }

    // -------------------------------------------------------------------------
    // getModuleMigrationFiles – path existence checks
    // -------------------------------------------------------------------------

    #[Test]
    public function it_returns_empty_array_when_neither_path_exists(): void
    {
        $tracker = app(MigrationTracker::class);

        $this->assertSame([], $tracker->getModuleMigrationFiles('FakeModule'));
    }

    #[Test]
    public function it_returns_migrations_from_vendor_path_only(): void
    {
        $this->createMigrationFile($this->vendorMigrationsPath, '2024_01_01_000000_create_fake_table.php');

        $tracker = app(MigrationTracker::class);
        $files = $tracker->getModuleMigrationFiles('FakeModule');

        $this->assertCount(1, $files);
        $this->assertContains('2024_01_01_000000_create_fake_table.php', $files);
    }

    #[Test]
    public function it_returns_migrations_from_published_path_only(): void
    {
        $this->createMigrationFile($this->publishedMigrationsPath, '2024_06_01_000000_create_published_table.php');

        $tracker = app(MigrationTracker::class);
        $files = $tracker->getModuleMigrationFiles('FakeModule');

        $this->assertCount(1, $files);
        $this->assertContains('2024_06_01_000000_create_published_table.php', $files);
    }

    #[Test]
    public function it_merges_migrations_from_both_paths(): void
    {
        $this->createMigrationFile($this->vendorMigrationsPath, '2024_01_01_000000_create_vendor_table.php');
        $this->createMigrationFile($this->publishedMigrationsPath, '2024_06_01_000000_create_published_table.php');

        $tracker = app(MigrationTracker::class);
        $files = $tracker->getModuleMigrationFiles('FakeModule');

        $this->assertCount(2, $files);
        $this->assertContains('2024_01_01_000000_create_vendor_table.php', $files);
        $this->assertContains('2024_06_01_000000_create_published_table.php', $files);
    }

    #[Test]
    public function it_deduplicates_migrations_present_in_both_paths(): void
    {
        $sharedFilename = '2024_01_01_000000_create_shared_table.php';
        $this->createMigrationFile($this->vendorMigrationsPath, $sharedFilename);
        $this->createMigrationFile($this->publishedMigrationsPath, $sharedFilename);

        $tracker = app(MigrationTracker::class);
        $files = $tracker->getModuleMigrationFiles('FakeModule');

        $this->assertCount(1, $files);
        $this->assertContains($sharedFilename, $files);
    }

    #[Test]
    public function it_skips_vendor_path_silently_when_it_does_not_exist(): void
    {
        // Only the published path exists
        $this->createMigrationFile($this->publishedMigrationsPath, '2024_06_01_000000_create_published_table.php');

        $tracker = app(MigrationTracker::class);

        // Should not throw; simply returns published migrations
        $files = $tracker->getModuleMigrationFiles('FakeModule');

        $this->assertCount(1, $files);
    }
}
