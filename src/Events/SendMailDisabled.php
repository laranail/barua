<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Events;

use Illuminate\Mail\Mailable;
use Illuminate\Foundation\Events\Dispatchable;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;

/**
 * Dispatched instead of sending when `laranail.barua.enable_send_mail` (or MailSender::setSendMail) is off.
 */
final readonly class SendMailDisabled
{
    use Dispatchable;

    public function __construct(
        public MailBuilder $mailBuilder,
        public DataBuilder $dataBuilder,
        public Mailable $mailable,
        public ErrorBuilder $errorBuilder,
    ) {}
}
