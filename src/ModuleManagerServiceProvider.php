<?php

namespace Nasirkhan\ModuleManager;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Nasirkhan\ModuleManager\Commands\AuthPermissionsCommand;
use Nasirkhan\ModuleManager\Commands\InsertDemoDataCommand;
use Nasirkhan\ModuleManager\Commands\ModuleBuildCommand;
use Nasirkhan\ModuleManager\Commands\ModuleCheckMigrationsCommand;
use Nasirkhan\ModuleManager\Commands\ModuleDependenciesCommand;
use Nasirkhan\ModuleManager\Commands\ModuleDetectUpdatesCommand;
use Nasirkhan\ModuleManager\Commands\ModuleDiffCommand;
use Nasirkhan\ModuleManager\Commands\ModuleGenerateTestCommand;
use Nasirkhan\ModuleManager\Commands\ModuleHelpCommand;
use Nasirkhan\ModuleManager\Commands\ModulePublishCommand;
use Nasirkhan\ModuleManager\Commands\ModuleStatusCommand;
use Nasirkhan\ModuleManager\Commands\ModuleTrackMigrationsCommand;
use Nasirkhan\ModuleManager\Services\MigrationTracker;
use Nasirkhan\ModuleManager\Services\ModuleVersion;

class ModuleManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register modules based on modules_statuses.json
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
            ], 'module-manager');

            $this->publishes([
                __DIR__.'/Modules/config/datatables.php' => config_path('datatables.php'),
            ], ['config', 'datatables-config']);

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

                    // Insert Demo Data Command
                    InsertDemoDataCommand::class,

                    // Auth Permission Command
                    AuthPermissionsCommand::class,

                    // Module Build Command to Create Module
                    ModuleBuildCommand::class,

                    // Module Remove Command
                    Commands\ModuleRemoveCommand::class,

                    // Module Disable Command
                    Commands\ModuleDisableCommand::class,

                    // Module Enable Command
                    Commands\ModuleEnableCommand::class,

                    // Updateability Commands
                    ModulePublishCommand::class,
                    ModuleStatusCommand::class,
                    ModuleDiffCommand::class,
                    ModuleCheckMigrationsCommand::class,
                    ModuleDependenciesCommand::class,
                    ModuleTrackMigrationsCommand::class,
                    ModuleDetectUpdatesCommand::class,
                    ModuleGenerateTestCommand::class,
                    ModuleHelpCommand::class,

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

        // Register ModuleVersion service
        $this->app->singleton(ModuleVersion::class, function () {
            return new ModuleVersion();
        });

        // Register MigrationTracker service
        $this->app->singleton(MigrationTracker::class, function () {
            return new MigrationTracker();
        });
    }

    public function registerModules()
    {
        $statusFile = base_path('modules_statuses.json');

        // Create default status file if it doesn't exist
        if (! File::exists($statusFile)) {
            $defaultModules = [
                'Post' => true,
                'Category' => true,
                'Tag' => true,
                'Menu' => true,
            ];
            File::put($statusFile, json_encode($defaultModules, JSON_PRETTY_PRINT));
        }

        $modules = json_decode(File::get($statusFile), true);

        if (! is_array($modules)) {
            return;
        }

        foreach ($modules as $module => $enabled) {
            if ($enabled !== true) {
                continue;
            }

            // Check if module is published (in Modules directory)
            $publishedProviderClass = "Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            // Check if module is in vendor package
            $vendorProviderClass = "Nasirkhan\\ModuleManager\\Modules\\{$module}\\Providers\\{$module}ServiceProvider";

            // Prefer published modules over vendor modules
            if (class_exists($publishedProviderClass)) {
                $this->app->register($publishedProviderClass);
            } elseif (class_exists($vendorProviderClass)) {
                $this->app->register($vendorProviderClass);
            }
        }
    }
}
