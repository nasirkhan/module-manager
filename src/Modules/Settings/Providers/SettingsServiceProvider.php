<?php

namespace Nasirkhan\ModuleManager\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'Settings';

    /**
     * @var string
     */
    protected $moduleNameLower = 'settings';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        // Load migrations from module
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish migrations with proper tags
        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['migrations', 'settings-migrations']);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $configPath = __DIR__.'/../Config/config.php';

        // Merge config from module (package defaults)
        $this->mergeConfigFrom($configPath, $this->moduleNameLower);

        // Publish config for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path($this->moduleNameLower.'.php'),
            ], ['config', 'settings-config', 'settings-module-config']);
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $sourcePath = __DIR__.'/../Resources/views';

        // Load views from module with 'settings' namespace
        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);

        // Publish views for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $sourcePath => resource_path('views/vendor/'.$this->moduleNameLower),
            ], ['views', 'settings-views', 'settings-module-views']);
        }
    }

    /**
     * Register translations.
     *
     * @return void
     */
    protected function registerTranslations()
    {
        $langPath = __DIR__.'/../lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
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
