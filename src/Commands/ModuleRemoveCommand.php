<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleRemoveCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'module:remove {moduleName : The name of the module to be removed} {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove an existing module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = Str::ucfirst(Str::singular(Str::studly($this->argument('moduleName'))));
        $config = config('module-manager');
        $namespace = $config['namespace'];
        $basePath = $namespace . '/' . $moduleName;

        if (! File::isDirectory($basePath)) {
            $this->components->error("Module {$moduleName} does not exist.");
            return;
        }

        if (! $this->option('force')) {
            if (! $this->components->confirm("Are you sure you want to remove the module {$moduleName}? This will permanently delete all files and cannot be undone.")) {
                $this->components->info('Operation cancelled.');
                return;
            }
        }

        $this->components->task("Removing Module: {$moduleName}", function () use ($basePath) {
            File::deleteDirectory($basePath);
        });

        $this->removeModuleFromStatus($moduleName);

        $this->components->info("Module {$moduleName} removed successfully.");
    }

    protected function removeModuleFromStatus($moduleName)
    {
        $destination = base_path('modules_statuses.json');

        if (File::exists($destination)) {
            $content = json_decode(File::get($destination), true);
            if (isset($content[$moduleName])) {
                unset($content[$moduleName]);
                File::put($destination, json_encode($content, JSON_PRETTY_PRINT));
            }
        }
    }
}
