<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Tests;

use Illuminate\Foundation\Auth\User;
use Simtabi\Laranail\Barua\Providers\BaruaServiceProvider;
use Simtabi\Laranail\Package\Tools\Testing\IsolatedTestCase;
use Simtabi\Laranail\Package\Tools\Providers\PackageToolsServiceProvider;

abstract class TestCase extends IsolatedTestCase
{
    /** @return list<class-string> */
    protected function getPackageProviders($app): array
    {
        return [PackageToolsServiceProvider::class, BaruaServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        // Generated per run: a key committed to a public repository is a key in the open.
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('mail.default', 'array');
        $app['config']->set('laranail.barua.user_class', User::class);
        $app['config']->set('laranail.barua.sender', ['email' => 'sender@example.com', 'name' => 'Sender']);
    }
}
