<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Blade;

/**
 * @return list<string> kebab-case names of every concrete component class
 */
function baruaComponents(): array
{
    $names = [];

    foreach (glob(dirname(__DIR__, 2) . '/src/View/Components/*.php') ?: [] as $file) {
        $class = basename($file, '.php');

        if ($class !== 'BaseComponent') {
            $names[] = Str::kebab($class);
        }
    }

    return $names;
}

it('finds the components it is about to render', function (): void {
    expect(baruaComponents())->toHaveCount(14);
});

it('renders every component (B3)', function (string $name): void {
    // Every component resolved its view as `barua::...` while the views were registered
    // under another name, so each one threw "No hint path defined".
    $attributes = match ($name) {
        'img'   => ' src="https://example.com/a.png"',
        'font'  => ' font-family="Inter"',
        default => '',
    };

    $html = Blade::render("<x-laranail-barua::{$name}{$attributes}>Inner text</x-laranail-barua::{$name}>");

    expect($html)->toBeString()->not->toBeEmpty();
})->with(baruaComponents());

it('passes slot content through a text component', function (): void {
    $html = Blade::render('<x-laranail-barua::text style="color: red">Hello there</x-laranail-barua::text>');

    expect($html)->toContain('Hello there')->toContain('<p')->toContain('color: red');
});

it('accepts an integer margin on a heading', function (): void {
    // The props are string|int, but the margin helper took only string: under strict_types an
    // integer `:m="4"` was a TypeError.
    $html = Blade::render('<x-laranail-barua::heading as="h2" :m="4">Title</x-laranail-barua::heading>');

    expect($html)->toContain('<h2')->toContain('margin:4px')->toContain('Title');
});

it('lets a specific margin override a general one', function (): void {
    $html = Blade::render('<x-laranail-barua::heading my="8" mt="2">T</x-laranail-barua::heading>');

    expect($html)->toContain('margin-top:2px')->toContain('margin-bottom:8px');
});
