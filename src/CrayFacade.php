<?php

namespace JunaidQadirB\Cray;

use Illuminate\Support\Facades\Facade;

class CrayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \JunaidQadirB\Cray\Console\Commands\Cray::class;
    }
}
