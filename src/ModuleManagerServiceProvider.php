<?php

namespace Nasirkhan\ModuleManager;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Nasirkhan\ModuleManager\Commands\ModuleBuildCommand;
use Nasirkhan\ModuleManager\Commands\TestCommand;

class ModuleManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerModules();

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'module-manager');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-manager');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('module-manager.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/stubs' => base_path('stubs/laravel-starter-stubs'),
            ], 'stubs');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/module-manager'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/module-manager'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/module-manager'),
            ], 'lang');*/

            /**
             * Registering package commands.
             * Register the command if we are using the application via the CLI.
             */
            if ($this->app->runningInConsole()) {
                $this->commands([
                    // TestCommand::class,
                    ModuleBuildCommand::class,
                ]);
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'module-manager');

        // Register the main class to use with the facade
        $this->app->singleton('module-manager', function () {
            return new ModuleManager();
        });
    }

    public function registerModules()
    {
        if (!File::exists(config('module-manager.files.module-list'))) {
            return;
        }

        $modules = json_decode(File::get(config('module-manager.files.module-list')));

        foreach ($modules as $module => $status) {
            if ($status) {
                $this->app->register('\Modules\\'.$module.'\Providers\\'.$module.'ServiceProvider');
            }
        }
    }
}
