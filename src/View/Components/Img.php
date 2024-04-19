<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Img extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public readonly string $src,
        public readonly string $alt = '',
        public readonly string $width = '100%',
        public readonly string $height = '100%',
    ) {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->getViewPath('img'));
    }
}
