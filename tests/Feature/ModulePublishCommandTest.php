<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModulePublishCommandTest extends TestCase
{
    /**
     * Remove any artifacts created during tests.
     */
    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('Modules/FakeModule'));
        File::deleteDirectory(base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule'));

        parent::tearDown();
    }

    #[Test]
    public function it_fails_when_module_does_not_exist_in_vendor(): void
    {
        $this->artisan('module:publish', ['module' => 'NonExistentModule9999'])
            ->assertExitCode(1);
    }

    #[Test]
    public function it_publishes_a_module_from_the_vendor_path(): void
    {
        // Create a fake module in the vendor source path.
        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        File::ensureDirectoryExists($vendorModulePath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // stub\n");

        $this->artisan('module:publish', ['module' => 'FakeModule', '--force' => true])
            ->assertExitCode(0);

        $this->assertDirectoryExists(base_path('Modules/FakeModule'));
    }

    #[Test]
    public function it_does_not_overwrite_published_module_without_force(): void
    {
        // Pre-create both source and destination.
        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        $publishedPath = base_path('Modules/FakeModule');
        File::ensureDirectoryExists($vendorModulePath);
        File::ensureDirectoryExists($publishedPath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // stub\n");

        // Without --force, the command should prompt; we provide "no" automatically.
        $this->artisan('module:publish', ['module' => 'FakeModule'])
            ->expectsConfirmation("Module 'FakeModule' already exists in Modules/. Overwrite?", 'no')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_marks_the_module_as_enabled_in_modules_statuses_after_publishing(): void
    {
        $statusFile = base_path('modules_statuses.json');
        File::put($statusFile, json_encode(['FakeModule' => true], JSON_PRETTY_PRINT));

        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        File::ensureDirectoryExists($vendorModulePath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // stub\n");

        $this->artisan('module:publish', ['module' => 'FakeModule', '--force' => true])
            ->assertExitCode(0);

        $statuses = json_decode(File::get($statusFile), true);

        $this->assertIsArray($statuses['FakeModule']);
        $this->assertTrue($statuses['FakeModule']['enabled'], 'Published module must have enabled=true in modules_statuses.json');
        $this->assertTrue($statuses['FakeModule']['published']);
    }

    #[Test]
    public function it_preserves_disabled_state_when_republishing_a_disabled_module(): void
    {
        $statusFile = base_path('modules_statuses.json');
        // Module was previously published and then disabled.
        File::put($statusFile, json_encode([
            'FakeModule' => [
                'enabled' => false,
                'published' => true,
                'published_at' => '2025-01-01T00:00:00Z',
                'location' => 'user',
                'version' => '1.0.0',
            ],
        ], JSON_PRETTY_PRINT));

        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        File::ensureDirectoryExists($vendorModulePath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // stub\n");

        $this->artisan('module:publish', ['module' => 'FakeModule', '--force' => true])
            ->assertExitCode(0);

        $statuses = json_decode(File::get($statusFile), true);

        $this->assertFalse($statuses['FakeModule']['enabled'], 'Re-publishing a disabled module must not re-enable it');
    }
}
