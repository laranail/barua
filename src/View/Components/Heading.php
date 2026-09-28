<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\View\Components;

use Closure;
use Illuminate\Contracts\View\View;

class Heading extends BaseComponent
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public readonly string $as = 'h1',
        public readonly string|int $m = '',
        public readonly string|int $mx = '',
        public readonly string|int $my = '',
        public readonly string|int $mt = '',
        public readonly string|int $mr = '',
        public readonly string|int $mb = '',
        public readonly string|int $ml = '',
    ) {}

    /**
     * @param array<string, string|int> $props
     */
    public function withMargin(array $props): string
    {
        // Later keys win, so `mt` overrides the top half of `my`, which overrides `m`.
        $styles = array_merge(
            $this->withSpace($props['m'] ?? '', ['margin']),
            $this->withSpace($props['mx'] ?? '', ['margin-left', 'margin-right']),
            $this->withSpace($props['my'] ?? '', ['margin-top', 'margin-bottom']),
            $this->withSpace($props['mt'] ?? '', ['margin-top']),
            $this->withSpace($props['mr'] ?? '', ['margin-right']),
            $this->withSpace($props['mb'] ?? '', ['margin-bottom']),
            $this->withSpace($props['ml'] ?? '', ['margin-left']),
        );

        return implode(';', array_map(
            static fn (string $property, string $value): string => "{$property}:{$value}",
            array_keys($styles),
            $styles,
        ));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->getViewPath('heading'));
    }

    /**
     * @param list<string> $properties
     *
     * @return array<string, string>
     */
    protected function withSpace(string|int $value, array $properties): array
    {
        $styles = [];

        foreach ($properties as $property) {
            // Check to ensure the value is a valid number
            if (is_numeric($value)) {
                $styles[$property] = $value . 'px';
            }
        }

        return $styles;
    }
}
