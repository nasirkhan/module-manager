<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Nasirkhan\ModuleManager\Modules\Post\Events\PostViewed;
use Nasirkhan\ModuleManager\Modules\Post\Listeners\PostViewed\IncrementPostHits;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        /**
         * Backend.
         */

        /**
         * Frontend.
         */
        PostViewed::class => [
            IncrementPostHits::class,
        ],
    ];
}
