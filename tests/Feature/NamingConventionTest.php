<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/*
 * Read from the live registries, not the provider source: what the framework ended up
 * holding is the thing that collides.
 */

it('registers views under the composer package name', function (): void {
    $hints = View::getFinder()->getHints();

    expect($hints)->toHaveKey('laranail/barua')
        ->and($hints)->not->toHaveKey('barua');
});

it('registers translations under the composer package name', function (): void {
    expect(Lang::getLoader()->namespaces())->toHaveKey('laranail/barua')
        ->and(Lang::getLoader()->namespaces())->not->toHaveKey('barua');
});

it('registers the component namespace with the hyphen prefix', function (): void {
    $namespaces = Blade::getClassComponentNamespaces();

    expect($namespaces)->toHaveKey('laranail-barua')
        ->and($namespaces)->not->toHaveKey('barua');
});

it('reads config at the vendor key', function (): void {
    expect(config('laranail.barua'))->toBeArray()->toHaveKey('dev_mode')
        ->and(config('barua'))->toBeNull();
});

it('publishes only vendor-scoped tags', function (): void {
    $tags = array_filter(
        ServiceProvider::publishableGroups(),
        static fn (string $tag): bool => str_contains($tag, 'barua'),
    );

    expect($tags)->not->toBeEmpty();

    foreach ($tags as $tag) {
        expect($tag)->toStartWith('laranail::barua-');
    }
});

it('reads configuration only at the registered key', function (): void {
    $this->assertReadsConfigAtRegisteredKey(dirname(__DIR__, 2) . '/src', 'barua');
});

it('proves a slash in a Blade tag is truncated, which is why the prefix keeps the hyphen', function (): void {
    preg_match('/<\s*x[-\:]([\w\-\:\.]*)/x', '<x-laranail/barua::text />', $m);

    expect($m[1])->toBe('laranail');
});
