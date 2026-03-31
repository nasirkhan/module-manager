<?php

namespace Nasirkhan\ModuleManager;

use Illuminate\Support\Facades\Facade;
use Nasirkhan\ModuleManager\Skeleton\SkeletonClass;

/**
 * @see SkeletonClass
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
