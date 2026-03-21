<?php

namespace Nasirkhan\ModuleManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application caches and reset permission cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Clearing application caches...');

        $commands = [
            'clear-compiled',
            'cache:clear',
            'route:clear',
            'view:clear',
            'config:clear',
            'cache:forget' => ['key' => 'spatie.permission.cache'],
            'permission:cache-reset',
        ];

        foreach ($commands as $command => $arguments) {
            if (is_int($command)) {
                $command = $arguments;
                $arguments = [];
            }

            $this->components->task("Running {$command}", function () use ($command, $arguments) {
                Artisan::call($command, $arguments, $this->output);

                return true;
            });
        }

        $this->newLine();
        $this->components->info('All caches cleared successfully.');

        return self::SUCCESS;
    }
}
