<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModuleGenerateTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-test {module : The module name}
                            {name : The test class name}
                            {--unit : Create a unit test instead of feature test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new test class for a module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $module = $this->argument('module');
        $name = $this->argument('name');
        $isUnit = $this->option('unit');

        $modulePath = base_path("vendor/nasirkhan/module-manager/src/Modules/{$module}");

        if (! File::exists($modulePath)) {
            $this->components->error("Module '{$module}' not found.");

            return self::FAILURE;
        }

        $testType = $isUnit ? 'Unit' : 'Feature';
        $testPath = $modulePath."/Tests/{$testType}";
        $testFile = $testPath.'/'.$name.'.php';

        if (! File::exists($testPath)) {
            File::makeDirectory($testPath, 0755, true);
        }

        if (File::exists($testFile)) {
            $this->components->error("Test '{$name}' already exists.");

            return self::FAILURE;
        }

        $stub = $this->getStub($isUnit);
        $content = $this->populateStub($stub, $module, $name);

        File::put($testFile, $content);

        $this->components->info("Test [{$testFile}] created successfully.");

        return self::SUCCESS;
    }

    /**
     * Get the stub file content.
     */
    protected function getStub(bool $isUnit): string
    {
        if ($isUnit) {
            return <<<'STUB'
<?php

namespace Nasirkhan\ModuleManager\Modules\{{module}}\Tests\Unit;

use PHPUnit\Framework\TestCase;

class {{class}} extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
STUB;
        }

        return <<<'STUB'
<?php

namespace Nasirkhan\ModuleManager\Modules\{{module}}\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class {{class}} extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
STUB;
    }

    /**
     * Populate the stub with actual values.
     */
    protected function populateStub(string $stub, string $module, string $name): string
    {
        return str_replace(
            ['{{module}}', '{{class}}'],
            [$module, $name],
            $stub
        );
    }
}
