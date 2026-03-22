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
    public function boot(): void
    {
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
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
