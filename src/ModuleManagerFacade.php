<?php

namespace Nasirkhan\ModuleManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Nasirkhan\ModuleManager\Skeleton\SkeletonClass
 */
class ModuleManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'module-manager';
    }
}
