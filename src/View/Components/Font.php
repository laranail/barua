<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Font extends BaseComponent
{
    /**
     * Create a new component instance.
     *
     * @param string|list<string> $fallbackFontFamily
     * @param array{url?: string, format?: string} $webFont
     */
    public function __construct(
        public readonly string $fontFamily,
        public readonly string|array $fallbackFontFamily = [
            'Arial', 'Inter', 'Helvetica', 'Verdana', 'Georgia',
            'Times New Roman', 'serif', 'sans-serif',
            'monospace', 'cursive', 'fantasy',
        ],
        public readonly array $webFont = [],
        public readonly string $fontStyle = 'normal',
        public readonly int $fontWeight = 400,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->getViewPath('font'));
    }
}
