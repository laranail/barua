<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Mail\Messages\Onboarding;

use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Mail\MailBase;
use Simtabi\Laranail\Barua\Builders\MailBuilder;

class WelcomeUser extends MailBase
{

    /**
     * Create a new message instance.
     *
     * @return void
     * @throws BaruaException
     */
    public function __construct(MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder)
    {
        $this->setView(view: 'onboarding.welcome_user');

        parent::__construct(
            mailBuilder: $mailBuilder->setView(view: $this->getView(), customPath: true),
            dataBuilder: $dataBuilder->setData(data: []),
            errorBuilder: $errorBuilder,
            className: get_class($this)
        );

        $this->setSubject(subject: "Welcome to :serviceName - Let's Get Started!");
    }

}
