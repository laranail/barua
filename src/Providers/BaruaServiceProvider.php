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
        $this->mergeConfigFrom($this->getPath('config/barua.php'), $this->getNamespace());

        $this->app->singleton(CssInlinerPlugin::class, function ($app) {
            return new CssInlinerPlugin($app['config']->get($this->getNamespace() . '.stylesheets', []));
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

        $getPrefix = function ($name) {
            return $this->getNamespace() . "::" . $name;
        };

        if ($this->app->runningInConsole()) {

            $this->publishes([
                $this->getPath('config/barua.php') => $this->app->configPath($this->getNamespace().'.php'),
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
        $this->loadViewComponentsAs(self::PROJECT_NAME, [
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
        return !empty($name) ? self::PROJECT_NAME . '-' . ltrim($name, '-') : self::PROJECT_NAME;
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
