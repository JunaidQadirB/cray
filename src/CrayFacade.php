<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JunaidQadirB\Cray\Skeleton\SkeletonClass
 */
class CrayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cray';
    }
}
