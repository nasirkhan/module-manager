<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleDiffCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule'));
        File::deleteDirectory(base_path('Modules/FakeModule'));

        parent::tearDown();
    }

    #[Test]
    public function it_fails_when_module_does_not_exist_in_vendor(): void
    {
        $this->artisan('module:diff', ['module' => 'ModuleThatDoesNotExist9999'])
            ->assertExitCode(1);
    }

    #[Test]
    public function it_reports_module_is_not_published_when_only_vendor_copy_exists(): void
    {
        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        File::ensureDirectoryExists($vendorModulePath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // stub\n");

        $this->artisan('module:diff', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_compares_vendor_and_published_versions(): void
    {
        $vendorModulePath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        $publishedPath = base_path('Modules/FakeModule');

        File::ensureDirectoryExists($vendorModulePath);
        File::ensureDirectoryExists($publishedPath);
        File::put("{$vendorModulePath}/FakeModuleServiceProvider.php", "<?php // vendor\n");
        File::put("{$publishedPath}/FakeModuleServiceProvider.php", "<?php // published\n");

        $this->artisan('module:diff', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }
}
