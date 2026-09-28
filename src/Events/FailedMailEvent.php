<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Events;

use Throwable;
use Illuminate\Mail\Mailable;
use Illuminate\Foundation\Events\Dispatchable;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;

/**
 * Dispatched when handing a message to the mailer threw.
 */
final readonly class FailedMailEvent
{
    use Dispatchable;

    public function __construct(
        public MailBuilder $mailBuilder,
        public DataBuilder $dataBuilder,
        public Mailable $mailable,
        public ErrorBuilder $errorBuilder,
        public Throwable $exception,
    ) {}
}
