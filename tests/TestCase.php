<?php

namespace Nasirkhan\ModuleManager\Tests;

use Nasirkhan\ModuleManager\ModuleManagerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            ModuleManagerServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Write an empty modules_statuses.json so no module service providers
        // (which may have third-party dependencies like Livewire) are booted
        // during the test run.
        $statusFile = $app->basePath('modules_statuses.json');
        file_put_contents($statusFile, '{}');
    }
}
