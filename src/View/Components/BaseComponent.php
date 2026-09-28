<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Illuminate\View\Component;
use Simtabi\Laranail\Barua\Support\Helpers;

abstract class BaseComponent extends Component
{
    public function getViewPath(string $view): string
    {
        return Helpers::getViewPath('components.' . trim($view));
    }
}
