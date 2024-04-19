<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua;

use DateInterval;
use DateTimeInterface;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Support\Helpers;

class Barua
{

    public function __construct()
    {
    }


    /**
     * @throws BaruaException
     */
    public function mailer(Mailable $mailable, MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder, bool $queued = false, DateTimeInterface|DateInterval|Carbon|int|null $delay = null)
    {

        $mailSender = new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder);

        return $mailSender->sendEmail(queued: $queued, delay: $delay);
    }

    public function url(?string $path = null): string
    {
        return Helpers::url($path);
    }

    public function asset(?string $path = null, bool $asRelativePath = false): string
    {
        $location = 'vendor/' . Helpers::NAMESPACE;
        $path     = (!empty($path) ? '/' . ltrim($path, '/') : '');

        if ($asRelativePath) {
          return app()->publicPath("{$location}{$path}");
        }

        return rtrim(Helpers::url($location), '/') . $path;
    }

}
