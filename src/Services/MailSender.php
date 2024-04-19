<?php declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Services;

use DateInterval;
use DateTimeInterface;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Support\TextFormatter;

class MailSender
{

    private ErrorBuilder $errorBuilder;

    private DataBuilder $dataBuilder;

    protected MailBuilder $mailBuilder;

    private Mailable $mailable;

    private bool|null $sendMail = null;

    /**
     * Initialise the settings and mailer.
     *
     * @param Mailable $mailable
     * @param MailBuilder $mailBuilder
     * @param DataBuilder $dataBuilder
     * @param ErrorBuilder $errorBuilder
     */
    public function __construct(Mailable $mailable, MailBuilder $mailBuilder, DataBuilder $dataBuilder, ErrorBuilder $errorBuilder)
    {
        $this->errorBuilder = $errorBuilder;
        $this->mailBuilder  = $mailBuilder;
        $this->dataBuilder  = $dataBuilder;
        $this->mailable     = $mailable;
    }

    public function setSendMail(bool $sendMail): static
    {
        $this->sendMail = $sendMail;
        return $this;
    }

    public function isSendMail(): bool|null
    {
        return $this->sendMail;
    }
    private function process(string $class, string $method, array $recipient, bool $queued, Mailable $mailable, DateTimeInterface|DateInterval|Carbon|int|null $delay = null): void
    {

        try {

            // Create the mail instance.
            $mail = $class::$method($recipient['email'], $recipient['name']);

            // Send the email.
            if ($queued) {
                if (!empty($delay)) {
                     $mail->later(delay: $delay, mailable: $mailable);
                } else {
                     $mail->queue(mailable: $mailable);
                }
            } else {
                 $mail->send(mailable: $mailable);
            }

            event(new SentMailEvent($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder));

        } catch (BaruaException|Exception $e) {
            event(new FailedMailEvent($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder, $e));

            $this->errorBuilder->setErrors(error: $e->getMessage(), key: 'send-mail');
        } catch (BaruaException $e) {
        }
    }

    /**
     * @throws BaruaException
     */
    public function sendEmail(bool $queued = false, DateTimeInterface|DateInterval|Carbon|int|null $delay = null): Mailable|int|bool
    {

        // Helper function to check if the given method exists.
        $methodExists = function (string|object $class, string $method) {
            $method = strtolower($method);
            if (!in_array($method, ['to', 'cc', 'bcc'], true) && !method_exists($class, $method)) {
                $this->errorBuilder->setErrors(error: "The addressing type '{$method}' is not supported.", key: 'addressing');
                return false;
            }

            return true;
        };

        // Set the subject and from address.
        $subject = null;
        if (!empty($this->mailBuilder->getSubject())) {
            $subject = $this->mailBuilder->getSubject();
        } elseif (!empty($this->mailable->getSubject())) {
            $subject = $this->mailable->getSubject();
        } else {
            $this->errorBuilder->setErrors(error: 'No subject found/defined.', key: 'send-mail-subject');
        }

        // Replace placeholders in the subject.
        $subject = TextFormatter::replacePlaceholders($subject, $this->dataBuilder->getData());

        // Set the subject and from address.
        $this->mailable->subject($subject);
        $this->mailable->from($this->mailBuilder->getFromEmail(), $this->mailBuilder->getFromName());

        // Add the view to the mailable.
        $mailable = Helpers::addView(
            mailBuilder: $this->mailBuilder,
            mailable: $this->mailable,
            errorBuilder: $this->errorBuilder,
            className: get_class($this),
            viewType: $this->mailBuilder->getViewType(),
            dataBuilder: $this->dataBuilder
        );

        // Check if sending mail feature is enabled.
        if (!is_null($this->isSendMail())) {
            $sendMail = $this->isSendMail();
        }else{
            $sendMail = Helpers::isSendMailEnabled();
        }

        if ($sendMail) {

            // Set the class to the Mail facade.
            $class  = Mail::class;

            // Add the recipients (to, cc, and bcc) to the mailable.
            foreach ($this->mailBuilder->getRecipients() as $method => $recipients) {
                $method = strtolower($method);
                if (!$methodExists($class, $method)) {
                    continue;
                }

                if (empty($recipients) || !is_array($recipients)) {
                    $this->errorBuilder->setErrors(error: "No '{$method}' recipients found.", key: 'send-mail-recipients');
                    continue;
                }

                foreach ($recipients as $recipient) {
                    if (is_array($recipient) && !empty($recipient['email'])) {
                        $this->process(class: $class, method: $method, recipient: $recipient, queued: $queued, mailable: $mailable, delay: $delay);
                    }
                }

            }

        } else {
            $this->errorBuilder->setErrors(error: 'Sending mail is disabled.', key: 'send-mail');
            event(new SentMailEvent($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder));
        }

        return $mailable;
    }

}
