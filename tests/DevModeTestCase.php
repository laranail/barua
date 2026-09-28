<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Tests;

/**
 * The application as a developer previewing templates would run it: dev mode on, `local`.
 */
abstract class DevModeTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['env'] = 'local';
        $app['config']->set('app.env', 'local');
        $app['config']->set('laranail.barua.dev_mode', true);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
    }
}
