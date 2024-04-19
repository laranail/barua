<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Hr extends BaseComponent
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
            <hr {{ $attributes->merge(['style' => 'width: 100%; border: none; border-top: 1px solid #eaeaea;']) }} />
        blade;
    }
}
