<?php

namespace Nasirkhan\ModuleManager\Modules\LogViewer\Providers;

use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'LogViewer';

    /**
     * @var string
     */
    protected $moduleNameLower = 'logviewer';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $configPath = __DIR__.'/../config/log-viewer.php';

        // Merge module config with app config
        $this->mergeConfigFrom($configPath, 'log-viewer');

        // Publish config for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('log-viewer.php'),
            ], ['config', 'logviewer-config', 'logviewer-module-config']);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
