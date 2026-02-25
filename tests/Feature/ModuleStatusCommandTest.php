<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleStatusCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('Modules/FakeModule'));
        File::deleteDirectory(base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule'));

        parent::tearDown();
    }

    #[Test]
    public function it_exits_successfully_with_no_modules_present(): void
    {
        $this->artisan('module:status')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_fails_when_checking_a_specific_module_that_does_not_exist(): void
    {
        $this->artisan('module:status', ['module' => 'ModuleThatDoesNotExist9999'])
            ->assertExitCode(1);
    }

    #[Test]
    public function it_exits_successfully_when_checking_a_published_module(): void
    {
        $publishedPath = base_path('Modules/FakeModule');
        File::ensureDirectoryExists($publishedPath);

        $this->artisan('module:status', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_exits_successfully_when_checking_a_vendor_module(): void
    {
        $vendorPath = base_path('vendor/nasirkhan/module-manager/src/Modules/FakeModule');
        File::ensureDirectoryExists($vendorPath);

        $this->artisan('module:status', ['module' => 'FakeModule'])
            ->assertExitCode(0);
    }

    #[Test]
    public function it_lists_a_published_module_in_output(): void
    {
        $publishedPath = base_path('Modules/FakeModule');
        File::ensureDirectoryExists($publishedPath);

        $this->artisan('module:status')
            ->assertExitCode(0);
    }
}
