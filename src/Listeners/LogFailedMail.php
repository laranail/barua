<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Listeners;

use Illuminate\Support\Facades\Log;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;

/**
 * Logs what happened, never the template data: that carries password-reset and
 * verification links and personal details, which do not belong in a log.
 */
final class LogFailedMail
{
    public function handle(FailedMailEvent $event): void
    {
        Log::error('laranail/barua: mail failed to send.', [
            'mailable'   => $event->mailable::class,
            'recipients' => array_sum(array_map(count(...), $event->mailBuilder->getRecipients())),
            'error'      => $event->exception->getMessage(),
        ]);
    }
}
