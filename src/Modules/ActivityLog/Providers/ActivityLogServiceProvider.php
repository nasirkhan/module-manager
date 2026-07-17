<?php

namespace Nasirkhan\ModuleManager\Modules\ActivityLog\Providers;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    protected $moduleName = 'ActivityLog';

    protected $moduleNameLower = 'activitylog';

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['migrations', 'activitylog-migrations']);
        }
    }

    public function register(): void
    {
        //
    }

    protected function registerConfig(): void
    {
        $configPath = __DIR__.'/../Config/config.php';

        $this->mergeConfigFrom($configPath, $this->moduleNameLower);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path($this->moduleNameLower.'.php'),
            ], ['config', 'activitylog-config']);
        }
    }

    protected function registerViews(): void
    {
        $sourcePath = __DIR__.'/../Resources/views';

        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $sourcePath => resource_path('views/vendor/'.$this->moduleNameLower),
            ], ['views', 'activitylog-views']);
        }
    }
}
