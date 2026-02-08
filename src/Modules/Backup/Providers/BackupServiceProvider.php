<?php

namespace Nasirkhan\ModuleManager\Modules\Backup\Providers;

use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'Backup';

    /**
     * @var string
     */
    protected $moduleNameLower = 'backup';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerViews();
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
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $sourcePath = __DIR__.'/../views';

        // Load views from module with 'backup' namespace
        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);

        // Publish views for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $sourcePath => resource_path('views/vendor/'.$this->moduleNameLower),
            ], ['views', 'backup-views', 'backup-module-views']);
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
