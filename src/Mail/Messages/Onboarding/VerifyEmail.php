<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Mail\Messages\Onboarding;

use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Mail\MailBase;
use Simtabi\Laranail\Barua\Builders\MailBuilder;

class VerifyEmail extends MailBase
{

    /**
     * Create a new message instance.
     *
     * @return void
     * @throws BaruaException
     */
    public function __construct(MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder)
    {
        $this->setView(view: 'onboarding.email_verification');

        parent::__construct(
            mailBuilder: $mailBuilder->setView(view: $this->getView(), customPath: true),
            dataBuilder: $dataBuilder->setData(data: []),
            errorBuilder: $errorBuilder,
            className: get_class($this)
        );

        $this->setSubject(subject: "Verify Your Email Address with :serviceName");
    }

}
