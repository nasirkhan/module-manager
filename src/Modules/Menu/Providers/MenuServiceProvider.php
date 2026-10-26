<?php

namespace Nasirkhan\ModuleManager\Modules\Menu\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nasirkhan\ModuleManager\Modules\Menu\Console\Commands\SeedMenuCommand;
use Nasirkhan\ModuleManager\Modules\Menu\Livewire\MenuItemComponent;
use Nasirkhan\ModuleManager\Modules\Menu\Models\Menu;
use Nasirkhan\ModuleManager\Modules\Menu\Models\MenuItem;
use Nasirkhan\ModuleManager\Modules\Menu\Observers\MenuItemObserver;
use Nasirkhan\ModuleManager\Modules\Menu\Observers\MenuObserver;
use Symfony\Component\Finder\Finder;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'Menu';

    /**
     * @var string
     */
    protected $moduleNameLower = 'menu';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load migrations from module
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish migrations with proper tags
        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], ['migrations', 'menu-migrations']);
        }

        // register commands
        $this->registerCommands('Nasirkhan\ModuleManager\Modules\Menu\Console\Commands');

        $this->registerLivewireComponents();

        // Register Blade components
        $this->registerBladeComponents();

        // Register seeders
        $this->registerSeeders();

        // Register model observers for automatic cache clearing
        $this->registerObservers();

        $this->notifyIfMigrationPending();
    }

    /**
     * Warn about pending migrations via the console and the admin backend.
     * The check is cached for 5 minutes; the migration's up() clears it immediately.
     */
    protected function notifyIfMigrationPending(): void
    {
        $isPending = Cache::remember('menu.migration.display_settings', 300, function () {
            try {
                return Schema::hasTable('menu_items')
                    && ! Schema::hasColumn('menu_items', 'display_settings');
            } catch (\Throwable) {
                return false;
            }
        });

        if (! $isPending) {
            return;
        }

        if ($this->app->runningInConsole()) {
            Event::listen(CommandStarting::class, function (CommandStarting $event): void {
                $event->output->writeln('');
                $event->output->writeln(' <bg=yellow;fg=black> MENU MODULE </> Pending migration detected: <comment>add_display_settings_to_menu_items</comment>');
                $event->output->writeln(' Run <info>php artisan migrate</info> to apply it.');
                $event->output->writeln('');
            });
        } else {
            View::composer('admin::layouts.admin', function ($view): void {
                $warnings = $view->getData()['pendingMigrationWarnings'] ?? [];
                $warnings[] = 'Menu module: run <code>php artisan migrate</code> to add the <code>display_settings</code> column.';
                $view->with('pendingMigrationWarnings', $warnings);
            });
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Event Service Provider
        $this->app->register(EventServiceProvider::class);
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
            ], ['config', 'menu-config', 'menu-module-config']);
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

        // Load views from module with 'menu' namespace
        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);

        // Publish views for customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $sourcePath => resource_path('views/vendor/'.$this->moduleNameLower),
            ], ['views', 'menu-views', 'menu-module-views']);
        }
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'menu');
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

    /**
     * Register commands.
     *
     * @param  string  $namespace
     */
    protected function registerCommands($namespace = '')
    {
        $consolePath = __DIR__.'/../Console';
        if (! is_dir($consolePath)) {
            return;
        }

        $finder = new Finder; // from Symfony\Component\Finder;
        $finder->files()->name('*.php')->in($consolePath);

        $classes = [];
        foreach ($finder as $file) {
            $class = $namespace.'\\'.$file->getBasename('.php');
            array_push($classes, $class);
        }

        $this->commands($classes);
    }

    /**
     * Register Blade components.
     *
     * @return void
     */
    protected function registerBladeComponents()
    {
        // Register anonymous Blade components
        Blade::component('menu::components.dynamic-menu', 'menu-dynamic-menu');
        Blade::component('menu::components.menu-item', 'menu-menu-item');
        Blade::component('menu::components.footer-menu-item', 'menu-footer-menu-item');
        Blade::component('menu::components.nav-item', 'menu-nav-item');
    }

    /**
     * Register Livewire components.
     *
     * @return void
     */
    protected function registerLivewireComponents()
    {
        // Register with proper namespace for module (use dot notation)
        Livewire::component('menu.menu-item-component', MenuItemComponent::class);

        // Publish Livewire components (both class and view) for full customization
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Livewire' => app_path('Livewire/Menu'),
                __DIR__.'/../Livewire' => resource_path('views/livewire/menu'),
            ], ['menu-livewire-components']);
        }
    }

    /**
     * Register module seeders.
     *
     * @return void
     */
    protected function registerSeeders()
    {
        // Publish seeders so they can be customized
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders/'.$this->moduleName),
            ], ['seeders', 'menu-seeders']);
        }

        // Register the seeder in the container for automatic discovery
        $this->app->singleton($this->moduleNameLower.'.database.seeder', function () {
            return 'Nasirkhan\\ModuleManager\\Modules\\'.$this->moduleName.'\\database\\seeders\\'.$this->moduleName.'DatabaseSeeder';
        });

        // Register a console command for seeding
        if ($this->app->runningInConsole()) {
            $this->commands([
                SeedMenuCommand::class,
            ]);
        }
    }

    /**
     * Register model observers for automatic cache clearing.
     *
     * @return void
     */
    protected function registerObservers()
    {
        Menu::observe(MenuObserver::class);
        MenuItem::observe(MenuItemObserver::class);
    }
}
