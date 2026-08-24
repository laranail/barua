<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Providers;

use Exception;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Plugins\CssInlinerPlugin;
use Simtabi\Laranail\Barua\View\Components\Body;
use Simtabi\Laranail\Barua\View\Components\Column;
use Simtabi\Laranail\Barua\View\Components\Container;
use Simtabi\Laranail\Barua\View\Components\Font;
use Simtabi\Laranail\Barua\View\Components\Head;
use Simtabi\Laranail\Barua\View\Components\Heading;
use Simtabi\Laranail\Barua\View\Components\Hr;
use Simtabi\Laranail\Barua\View\Components\Html;
use Simtabi\Laranail\Barua\View\Components\Img;
use Simtabi\Laranail\Barua\View\Components\Link;
use Simtabi\Laranail\Barua\View\Components\Row;
use Simtabi\Laranail\Barua\View\Components\Section;
use Simtabi\Laranail\Barua\View\Components\Td;
use Simtabi\Laranail\Barua\View\Components\Text;

class BaruaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    private const string BASE_PATH = __DIR__.'/../../';

    private const string PROJECT_NAME = 'barua';

    /** Views, translations and the Blade component prefix — org shape. */
    private const string VENDORED_NAMESPACE = 'laranail-barua';

    /** The flat org config key. */
    private const string CONFIG_KEY = 'laranail.barua';


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getPath('config/barua.php'), self::CONFIG_KEY);

        $this->app->singleton(CssInlinerPlugin::class, function ($app) {
            return new CssInlinerPlugin($app['config']->get(self::CONFIG_KEY . '.stylesheets', []));
        });

        Event::listen(MessageSending::class, CssInlinerPlugin::class);

        $this->registerMailer();
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this
            ->bootResources()
            ->bootPublishing()
            ->bootComponents()
            ->loadRoutes();

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Boot the package resources.
     */
    protected function bootResources(): static
    {
        $this->loadViewsFrom($this->getPath('resources/views'), $this->getNamespace());

        $this->loadMigrationsFrom($this->getPath('database/migrations'));

        $this->loadTranslationsFrom($this->getPath('resources/lang'), $this->getNamespace());

        return $this;
    }

    /**
     * Boot the package's publishable resources.
     */
    protected function bootPublishing(): static
    {

        // Publish tags follow the org command-style shape: the registry
        // resolves exact names, so `laranail::barua-config` is collision-proof.
        $getPrefix = function ($name) {
            return 'laranail::' . self::PROJECT_NAME . '-' . $name;
        };

        if ($this->app->runningInConsole()) {

            $this->publishes([
                $this->getPath('config/barua.php') => $this->app->configPath('laranail/barua.php'),
            ], $getPrefix('config'));

            $this->publishes([
                $this->getPath('resources/views/templates') => $this->app->resourcePath('views/vendor/'.$this->getNamespace()),
            ], $getPrefix('blade-templates'));

            $this->publishes([
                $this->getPath('resources/views/components/') => $this->app->resourcePath('views/vendor/components/'.$this->getNamespace()),
            ], $getPrefix('blade-components'));

            $this->publishes([
                $this->getPath('src/View/Components/') => $this->app->basePath('View/Components'),
            ], $getPrefix('blade-component-classes'));

            $this->publishes([
                $this->getPath('resources/lang/en') => $this->app->resourcePath('lang/en'),
            ], $getPrefix('lang'));

            $this->publishes([
                $this->getPath('public/assets') => $this->app->basePath('public/vendor/'.$this->getNamespace()),
            ], $getPrefix('public-assets'));

            $this->publishes([
                $this->getPath('src/Mail/Templates') => $this->app->basePath('app/Mail/Templates'),
            ], $getPrefix('mail-template-classes'));

            $this->publishes([
                $this->getPath('database/migrations') => $this->app->databasePath('migrations'),
            ], $getPrefix('migrations'));

            $this->updateNamespaceInDirectory();
        }

        return $this;
    }

    /**
     * Boot the package components.
     */
    protected function bootComponents(): static
    {
        $this->loadViewComponentsAs(self::VENDORED_NAMESPACE, [
            Head::class,
            Body::class,
            Html::class,
            Hr::class,
            Row::class,
            Column::class,
            Section::class,
            Text::class,
            Img::class,
            Font::class,
            Link::class,
            Heading::class,
            Container::class,
            Td::class,
        ]);

        return $this;
    }

    private function getNamespace(?string $name = null): string
    {
        return !empty($name) ? self::VENDORED_NAMESPACE . '-' . ltrim($name, '-') : self::VENDORED_NAMESPACE;
    }

    private function getPath(?string $path = null): string
    {
        return !empty($path) ? self::BASE_PATH . ltrim($path, '/') : self::BASE_PATH;
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return BaruaServiceProvider
     */
    protected function loadRoutes(): static
    {
        $this->loadRoutesFrom($this->getPath('routes/web.php'));

        return $this;
    }

    public function updateNamespaceInDirectory(): array
    {
        return Helpers::updateNamespaceInDirectory(
            directory: $this->app->basePath('app/Mail/Messages'),
            oldNamespace: 'namespace Simtabi\Laranail\Barua\Mail\Messages',
            newNamespace: 'namespace App\Mail\Messages');
    }

    private function registerMailer(): void
    {
        $this->app->singleton('mailer', function ($app) {
            $config = $app->make('config')->get('mail');
            return new CustomMailer(
                $app->make('view'), $app->make('swift.mailer'), $app->make('events'), $config
            );
        });
    }

}
