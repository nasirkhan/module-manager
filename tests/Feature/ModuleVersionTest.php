<?php

namespace Nasirkhan\ModuleManager\Tests\Feature;

use Illuminate\Support\Facades\File;
use Nasirkhan\ModuleManager\Services\ModuleVersion;
use Nasirkhan\ModuleManager\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleVersionTest extends TestCase
{
    private string $publishedPath;

    /** Mirrors the fallback path used by ModuleVersion::getModulePath() (src/Modules/{module}). */
    private string $vendorPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publishedPath = base_path('Modules/FakeModule');
        // getModulePath() falls back to __DIR__.'/../Modules/{module}' which,
        // from src/Services/, resolves to src/Modules/{module}.
        $this->vendorPath = dirname(__DIR__, 2).'/src/Modules/FakeModule';
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(base_path('Modules/FakeModule'));
        File::deleteDirectory($this->vendorPath);

        parent::tearDown();
    }

    private function makeModuleJson(string $dir, array $data): void
    {
        File::ensureDirectoryExists($dir);
        File::put("{$dir}/module.json", json_encode($data, JSON_PRETTY_PRINT));
    }

    // -------------------------------------------------------------------------
    // getModulePath / getModuleData
    // -------------------------------------------------------------------------

    #[Test]
    public function it_returns_empty_array_when_module_json_does_not_exist(): void
    {
        File::ensureDirectoryExists($this->publishedPath);

        $service = app(ModuleVersion::class);

        $this->assertSame([], $service->getModuleData('FakeModule'));
    }

    #[Test]
    public function it_reads_module_data_from_published_path(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '2.0.0', 'description' => 'Published']);

        $service = app(ModuleVersion::class);
        $data = $service->getModuleData('FakeModule');

        $this->assertSame('2.0.0', $data['version']);
        $this->assertSame('Published', $data['description']);
    }

    #[Test]
    public function it_reads_module_data_from_vendor_path_when_not_published(): void
    {
        $this->makeModuleJson($this->vendorPath, ['version' => '1.0.0', 'description' => 'Vendor']);

        $service = app(ModuleVersion::class);
        $data = $service->getModuleData('FakeModule');

        $this->assertSame('1.0.0', $data['version']);
    }

    #[Test]
    public function it_prefers_published_module_over_vendor_module(): void
    {
        $this->makeModuleJson($this->vendorPath, ['version' => '1.0.0']);
        $this->makeModuleJson($this->publishedPath, ['version' => '2.5.0']);

        $service = app(ModuleVersion::class);

        $this->assertSame('2.5.0', $service->getVersion('FakeModule'));
    }

    // -------------------------------------------------------------------------
    // getVersion
    // -------------------------------------------------------------------------

    #[Test]
    public function it_returns_null_version_when_module_does_not_exist(): void
    {
        $service = app(ModuleVersion::class);

        $this->assertNull($service->getVersion('NonExistentModuleXYZ'));
    }

    #[Test]
    public function it_returns_version_from_published_module(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '3.1.0']);

        $service = app(ModuleVersion::class);

        $this->assertSame('3.1.0', $service->getVersion('FakeModule'));
    }

    // -------------------------------------------------------------------------
    // getAllVersions
    // -------------------------------------------------------------------------

    #[Test]
    public function it_includes_published_modules_in_all_versions(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '4.0.0']);

        $service = app(ModuleVersion::class);
        $versions = $service->getAllVersions();

        $this->assertArrayHasKey('FakeModule', $versions);
        $this->assertSame('4.0.0', $versions['FakeModule']['version']);
    }

    #[Test]
    public function it_includes_vendor_modules_in_all_versions(): void
    {
        $this->makeModuleJson($this->vendorPath, ['version' => '1.2.3']);

        $service = app(ModuleVersion::class);
        $versions = $service->getAllVersions();

        $this->assertArrayHasKey('FakeModule', $versions);
        $this->assertSame('1.2.3', $versions['FakeModule']['version']);
    }

    #[Test]
    public function it_deduplicates_modules_present_in_both_vendor_and_published(): void
    {
        $this->makeModuleJson($this->vendorPath, ['version' => '1.0.0']);
        $this->makeModuleJson($this->publishedPath, ['version' => '2.0.0']);

        $service = app(ModuleVersion::class);
        $versions = $service->getAllVersions();

        // Should appear only once
        $this->assertCount(
            1,
            array_filter(array_keys($versions), fn ($k) => $k === 'FakeModule')
        );

        // Published version wins
        $this->assertSame('2.0.0', $versions['FakeModule']['version']);
    }

    // -------------------------------------------------------------------------
    // versionMatches / versionSatisfies
    // -------------------------------------------------------------------------

    #[Test]
    public function it_returns_false_for_version_matches_when_module_missing(): void
    {
        $service = app(ModuleVersion::class);

        $this->assertFalse($service->versionMatches('NonExistentModuleXYZ', '1.0.0'));
    }

    #[Test]
    public function it_returns_true_when_version_matches_exactly(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '1.0.0']);

        $service = app(ModuleVersion::class);

        $this->assertTrue($service->versionMatches('FakeModule', '1.0.0'));
        $this->assertFalse($service->versionMatches('FakeModule', '1.0.1'));
    }

    #[Test]
    public function it_returns_true_when_version_satisfies_requirement(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '2.0.0']);

        $service = app(ModuleVersion::class);

        $this->assertTrue($service->versionSatisfies('FakeModule', '1.0.0'));
        $this->assertTrue($service->versionSatisfies('FakeModule', '2.0.0'));
        $this->assertFalse($service->versionSatisfies('FakeModule', '3.0.0'));
    }

    // -------------------------------------------------------------------------
    // getDependencies / dependenciesSatisfied
    // -------------------------------------------------------------------------

    #[Test]
    public function it_returns_empty_dependencies_when_none_defined(): void
    {
        $this->makeModuleJson($this->publishedPath, ['version' => '1.0.0']);

        $service = app(ModuleVersion::class);

        $this->assertSame([], $service->getDependencies('FakeModule'));
    }

    #[Test]
    public function it_returns_all_dependencies_as_missing_when_they_are_not_installed(): void
    {
        $this->makeModuleJson($this->publishedPath, [
            'version' => '1.0.0',
            'requires' => ['MissingModuleA', 'MissingModuleB'],
        ]);

        $service = app(ModuleVersion::class);
        $result = $service->dependenciesSatisfied('FakeModule');

        $this->assertFalse($result['all_satisfied']);
        $this->assertCount(2, $result['missing']);
        $this->assertEmpty($result['satisfied']);
    }
}
