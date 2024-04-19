<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Illuminate\View\Component;

abstract class BaseComponent extends Component
{

    protected string $namespace = 'barua';

    public function __construct()
    {
        //
    }

    public function getViewPath(string $view): string
    {
        return ($this->namespace . '::components.' . trim($view));
    }

}