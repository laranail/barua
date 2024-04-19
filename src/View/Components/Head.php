<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Head extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <head {{ $attributes }}>
                <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                {{ $slot }}
            </head>
        blade;
    }
}
