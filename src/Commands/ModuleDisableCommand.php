<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleDisableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'module:disable {moduleName : The name of the module to be disabled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable an existing module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $moduleName = Str::ucfirst(Str::singular(Str::studly($this->argument('moduleName'))));

        $destination = base_path('modules_statuses.json');

        if (! File::exists($destination)) {
            $this->components->error('Module status file not found.');

            return;
        }

        $content = json_decode(File::get($destination), true);

        if (! isset($content[$moduleName])) {
            $this->components->error("Module {$moduleName} not found in status file.");

            return;
        }

        if ($content[$moduleName] === false) {
            $this->components->info("Module {$moduleName} is already disabled.");

            return;
        }

        $content[$moduleName] = false;
        File::put($destination, json_encode($content, JSON_PRETTY_PRINT));

        $this->components->info("Module {$moduleName} disabled successfully.");
    }
}
