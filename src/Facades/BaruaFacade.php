<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Facades;

use Illuminate\Support\Facades\Facade;
use Simtabi\Laranail\Barua\Barua;

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