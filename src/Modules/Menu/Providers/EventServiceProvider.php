<?php

namespace Nasirkhan\ModuleManager\Modules\Menu\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    protected function configureEmailVerification(): void
    {
        // Email verification is handled by the application's AppServiceProvider.
    }
}
