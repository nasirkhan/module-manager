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
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
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
