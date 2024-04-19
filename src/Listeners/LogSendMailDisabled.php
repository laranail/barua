<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Listeners;


use Illuminate\Support\Facades\Log;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;

class LogSendMailDisabled
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
    public function handle(FailedMailEvent $event): void
    {
        $errorBuilder = $event->errorBuilder;
        $mailBuilder  = $event->mailBuilder;
        $dataBuilder  = $event->dataBuilder;
        $mailable     = $event->mailable;
        $exception    = $event->exception;

        Log::error("Email failed to send", ['recipient' => $mailBuilder->getRecipients(), 'data' => $dataBuilder->getData(), 'error' => $exception->getMessage()]);
    }
}
