<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Listeners;

use Illuminate\Support\Facades\Log;
use Simtabi\Laranail\Barua\Events\SentMailEvent;

class LogSentMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SentMailEvent $event): void
    {
        $errorBuilder = $event->errorBuilder;
        $mailBuilder  = $event->mailBuilder;
        $dataBuilder  = $event->dataBuilder;
        $mailable     = $event->mailable;

        Log::info("Email successfully sent for: ", ['recipient' => $mailBuilder->getRecipients(), 'data' => $dataBuilder->getData(),]);
    }
}
