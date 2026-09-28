<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Facades;

use Simtabi\Laranail\Barua\Barua;
use Illuminate\Support\Facades\Facade;

class BaruaFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Barua::class;
    }
}
