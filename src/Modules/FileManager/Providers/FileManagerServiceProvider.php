<?php

namespace Nasirkhan\ModuleManager\Modules\FileManager\Providers;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'FileManager';

    /**
     * @var string
     */
    protected $moduleNameLower = 'filemanager';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->app->register(RouteServiceProvider::class);
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
        $configPath = __DIR__.'/../Config/lfm.php';

        // Merge config from module (package defaults)
        $this->mergeConfigFrom($configPath, 'lfm');

        // Publish config for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('lfm.php'),
            ], ['config', 'filemanager-config', 'lfm-config']);
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
