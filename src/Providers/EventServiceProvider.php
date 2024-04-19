<?php

namespace Simtabi\Laranail\Barua\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Listeners\LogFailedMail;
use Simtabi\Laranail\Barua\Listeners\LogSentMail;
use Simtabi\Laranail\Barua\Listeners\MailSentListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SentMailEvent::class => [
            LogSentMail::class,
        ],
        FailedMailEvent::class => [
            LogFailedMail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $events = $this->app['events'];
    }

    /**
     * Determine if events and listeners should be discovered automatically.
     *
     * Laravel can automatically discover events and listeners, rather than manually registering them in the $listen array.
     * This is optional and based on your preference.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}