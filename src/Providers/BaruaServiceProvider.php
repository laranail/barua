<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Mail\Events\MessageSending;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Listeners\LogSentMail;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;
use Simtabi\Laranail\Barua\Events\SendMailDisabled;
use Simtabi\Laranail\Barua\Listeners\LogFailedMail;
use Simtabi\Laranail\Barua\Plugins\CssInlinerPlugin;
use Simtabi\Laranail\Barua\Listeners\LogSendMailDisabled;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;

/**
 * Registers barua's config, views, translations and Blade components, and nothing that
 * belongs to the host application.
 *
 * Two things the previous provider did are gone on purpose. It rebound the container's
 * `mailer` to a class that did not exist, built from `swift.mailer` (removed in Laravel 9),
 * so every host that sent mail fataled. And on every console boot it rewrote PHP files in
 * the host's `app/Mail/Messages`. A package must not do either.
 */
final class BaruaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/barua')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            // `<x-laranail-barua::text />`, `<x-laranail-barua::img src="..." />`, ...
            ->hasBladeComponentNamespace('Simtabi\\Laranail\\Barua\\View\\Components', Helpers::COMPONENT_PREFIX)
            ->registerEventListener(SentMailEvent::class, LogSentMail::class)
            ->registerEventListener(FailedMailEvent::class, LogFailedMail::class)
            ->registerEventListener(SendMailDisabled::class, LogSendMailDisabled::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(
            CssInlinerPlugin::class,
            static fn ($app): CssInlinerPlugin => new CssInlinerPlugin(
                (array) $app['config']->get(Helpers::CONFIG_KEY . '.stylesheets', []),
                public_path(),
            ),
        );
    }

    public function packageBooted(): void
    {
        if (Helpers::isCssInliningEnabled()) {
            Event::listen(MessageSending::class, CssInlinerPlugin::class);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->package->basePath('/public/assets') => public_path('vendor/' . Helpers::VIEW_NAMESPACE),
            ], $this->package->getNamespacedPublishTag('assets'));
        }

        // The debug pages send real mail from GET requests, so they exist only when asked
        // for AND only in the local environment. Checked at boot: `route:cache` keeps
        // whichever decision was in force when it ran.
        if (Helpers::isDevModeEnable() && $this->app->environment('local')) {
            Route::middleware('web')->group($this->package->basePath('/routes/web.php'));
        }
    }
}
