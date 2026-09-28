<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Events;

use Illuminate\Mail\Mailable;
use Illuminate\Foundation\Events\Dispatchable;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;

/**
 * Dispatched after a message was handed to the mailer (sent, or queued).
 */
final readonly class SentMailEvent
{
    use Dispatchable;

    public function __construct(
        public MailBuilder $mailBuilder,
        public DataBuilder $dataBuilder,
        public Mailable $mailable,
        public ErrorBuilder $errorBuilder,
    ) {}
}
