<?php

namespace {{namespace}}\{{moduleName}}\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use {{namespace}}\{{moduleName}}\Livewire\Backend\{{moduleNamePlural}}Index;
use Symfony\Component\Finder\Finder;

class {{moduleName}}ServiceProvider extends ServiceProvider
{
    protected $moduleName = '{{moduleName}}';

    protected $moduleNameLower = '{{moduleNameLower}}';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['migrations', '{{moduleNameLower}}-migrations']);
        }

        $this->registerCommands('{{namespace}}\{{moduleName}}\Console\Commands');
        $this->registerSeeders();
        $this->registerLivewireComponents();
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $configPath = __DIR__.'/../Config/config.php';

        $this->mergeConfigFrom($configPath, $this->moduleNameLower);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path($this->moduleNameLower.'.php'),
            ], ['config', '{{moduleNameLower}}-config', '{{moduleNameLower}}-module-config']);
        }
    }

    public function registerViews(): void
    {
        $sourcePath = __DIR__.'/../Resources/views';

        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $sourcePath => resource_path('views/vendor/'.$this->moduleNameLower),
            ], ['views', '{{moduleNameLower}}-views', '{{moduleNameLower}}-module-views']);

            $this->publishes([
                $sourcePath.'/livewire' => resource_path('views/vendor/'.$this->moduleNameLower.'/livewire'),
            ], ['{{moduleNameLower}}-livewire-views']);
        }
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', '{{moduleNameLower}}');
    }

    public function provides(): array
    {
        return [];
    }

    protected function registerCommands(string $namespace = ''): void
    {
        $consolePath = __DIR__.'/../Console';
        if (! is_dir($consolePath)) {
            return;
        }

        $finder = new Finder;
        $finder->files()->name('*.php')->in($consolePath);

        $classes = [];
        foreach ($finder as $file) {
            $class = $namespace.'\\'.$file->getBasename('.php');
            array_push($classes, $class);
        }

        $this->commands($classes);
    }

    protected function registerSeeders(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders/'.$this->moduleName),
            ], ['seeders', '{{moduleNameLower}}-seeders']);
        }

        $this->app->singleton($this->moduleNameLower.'.database.seeder', function () {
            return '{{namespace}}\\{{moduleName}}\\database\\seeders\\{{moduleName}}DatabaseSeeder';
        });
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('{{moduleNameLower}}.backend-{{moduleNameLowerPlural}}-index', {{moduleNamePlural}}Index::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Livewire' => app_path('Livewire/{{moduleName}}'),
                __DIR__.'/../Resources/views/livewire' => resource_path('views/livewire/{{moduleNameLower}}'),
            ], ['{{moduleNameLower}}-livewire-components']);
        }
    }
}
