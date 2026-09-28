<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Simtabi\Laranail\Barua\Providers\BaruaServiceProvider;

it('binds nothing named mailer (B1)', function (): void {
    // The old provider bound `mailer` to a class that does not exist. In a full application
    // Laravel's deferred MailServiceProvider re-binds `mailer` on first resolve, which hid the
    // defect from any "mail still works" test. So register barua alone, into an application
    // with no mail provider at all: any `mailer` binding there can only be barua's.
    $app = new Application(dirname(__DIR__, 2));
    $app->instance('config', new Repository);

    $provider = new BaruaServiceProvider($app);
    $provider->register();

    expect($app->bound('mailer'))->toBeFalse()
        ->and($app->bound('swift.mailer'))->toBeFalse();
});

it('runs no migrations in the host (B12)', function (): void {
    $paths = $this->app->make('migrator')->paths();

    expect(array_filter($paths, static fn (string $path): bool => str_contains($path, 'barua')))->toBeEmpty();
});

it('registers no debug routes unless asked (B4)', function (): void {
    $names = array_keys(Route::getRoutes()->getRoutesByName());

    expect(array_filter($names, static fn (string $name): bool => str_contains($name, 'barua')))->toBeEmpty();
});

it('does not rewrite files in the host application on boot (B2)', function (): void {
    $dir = $this->app->basePath('app/Mail/Messages');
    $file = $dir . '/Canary.php';

    @mkdir($dir, 0o777, true);
    file_put_contents($file, "<?php\nnamespace Simtabi\\Laranail\\Barua\\Mail\\Messages;\n");

    try {
        $provider = new BaruaServiceProvider($this->app);
        $provider->register();
        $provider->boot();

        expect(file_get_contents($file))->toContain('namespace Simtabi\\Laranail\\Barua\\Mail\\Messages');
    } finally {
        @unlink($file);
    }
});
