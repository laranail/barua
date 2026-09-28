<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Mail\Messages\Marketing;

use Simtabi\Laranail\Barua\Mail\MailBase;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

class PaymentConfirmation extends MailBase
{
    /**
     * Create a new message instance.
     *
     * @throws BaruaException
     */
    public function __construct(MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder)
    {
        $this->setView(view: 'marketing.payment_confirmation');

        parent::__construct(
            mailBuilder: $mailBuilder->setView(view: $this->getView()),
            dataBuilder: $dataBuilder->setData(data: []),
            errorBuilder: $errorBuilder,
            className: static::class,
        );

        $this->setSubject(subject: 'Payment Confirmation for Your Order at :serviceName');
    }
}
