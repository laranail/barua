<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Barua\Services;

use Throwable;
use DateInterval;
use DateTimeInterface;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Simtabi\Laranail\Barua\Mail\MailBase;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Support\TextFormatter;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;
use Simtabi\Laranail\Barua\Events\SendMailDisabled;
use Simtabi\Laranail\Barua\Exceptions\BaruaException;

class MailSender
{
    private ?bool $sendMail = null;

    /**
     * Initialise the settings and mailer.
     */
    public function __construct(private readonly Mailable $mailable, protected MailBuilder $mailBuilder, private readonly DataBuilder $dataBuilder, private readonly ErrorBuilder $errorBuilder) {}

    public function setSendMail(bool $sendMail): static
    {
        $this->sendMail = $sendMail;

        return $this;
    }

    public function isSendMail(): ?bool
    {
        return $this->sendMail;
    }

    /**
     * @throws BaruaException
     */
    public function sendEmail(bool $queued = false, DateTimeInterface|DateInterval|Carbon|int|null $delay = null): Mailable|false
    {

        // Helper function to check if the given method exists.
        $methodExists = function (string|object $class, string $method): bool {
            $method = strtolower($method);
            if (! in_array($method, ['to', 'cc', 'bcc'], true) && ! method_exists($class, $method)) {
                $this->errorBuilder->setErrors(error: "The addressing type '{$method}' is not supported.", key: 'addressing');

                return false;
            }

            return true;
        };

        // Resolve the subject: the builder's, else the mailable's.
        $subject = null;
        if (! empty($this->mailBuilder->getSubject())) {
            $subject = $this->mailBuilder->getSubject();
        } elseif (($own = $this->mailableSubject()) !== null) {
            $subject = $own;
        } else {
            $this->errorBuilder->setErrors(error: 'No subject found/defined.', key: 'send-mail-subject');
        }

        // Replace placeholders in the subject.
        if ($subject !== null) {
            $this->mailable->subject(TextFormatter::replacePlaceholders($subject, $this->dataBuilder->getData()));
        }
        $sender = Helpers::getSender();
        $from = $this->mailBuilder->getFromEmail() ?? $sender->email;

        if ($from !== null) {
            $this->mailable->from($from, $this->mailBuilder->getFromName() ?? $sender->name);
        }

        // Add the view to the mailable. On failure the reason is on the error builder.
        $mailable = Helpers::addView(
            mailBuilder: $this->mailBuilder,
            mailable: $this->mailable,
            errorBuilder: $this->errorBuilder,
            className: $this->mailable::class,
            viewType: $this->mailBuilder->getViewType(),
            dataBuilder: $this->dataBuilder,
        );

        if ($mailable === false) {
            return false;
        }

        // Check if sending mail feature is enabled.
        if (! is_null($this->isSendMail())) {
            $sendMail = $this->isSendMail();
        } else {
            $sendMail = Helpers::isSendMailEnabled();
        }

        if ($sendMail) {

            // Set the class to the Mail facade.
            $class = Mail::class;

            // Add the recipients (to, cc, and bcc) to the mailable.
            foreach ($this->mailBuilder->getRecipients() as $method => $recipients) {
                $method = strtolower($method);
                if (! $methodExists($class, $method)) {
                    continue;
                }

                if (empty($recipients) || ! is_array($recipients)) {
                    $this->errorBuilder->setErrors(error: "No '{$method}' recipients found.", key: 'send-mail-recipients');

                    continue;
                }

                foreach ($recipients as $recipient) {
                    if (is_array($recipient) && ! empty($recipient['email'])) {
                        $this->process(class: $class, method: $method, recipient: $recipient, queued: $queued, mailable: $mailable, delay: $delay);
                    }
                }

            }

        } else {
            event(new SendMailDisabled($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder));
            $this->errorBuilder->setErrors(error: 'Sending mail is disabled.', key: 'send-mail');
        }

        return $mailable;
    }

    /**
     * @param array{email: string, name: string|null} $recipient
     */
    private function process(string $class, string $method, array $recipient, bool $queued, Mailable $mailable, DateTimeInterface|DateInterval|Carbon|int|null $delay = null): void
    {

        try {

            // Create the mail instance.
            $mail = $class::$method($recipient['email'], $recipient['name']);

            // Send the email.
            if ($queued) {
                if (! empty($delay)) {
                    $mail->later(delay: $delay, mailable: $mailable);
                } else {
                    $mail->queue(mailable: $mailable);
                }
            } else {
                $mail->send(mailable: $mailable);
            }

            event(new SentMailEvent($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder));

        } catch (Throwable $e) {
            event(new FailedMailEvent($this->mailBuilder, $this->dataBuilder, $mailable, $this->errorBuilder, $e));

            $this->errorBuilder->setErrors(error: $e->getMessage(), key: 'send-mail');
        }
    }

    /**
     * The subject the mailable carries: barua's own mailables keep it in their options, a plain
     * Laravel mailable in its public `$subject`. Calling getSubject() on the latter was an error.
     */
    private function mailableSubject(): ?string
    {
        $subject = $this->mailable instanceof MailBase ? $this->mailable->getSubject() : $this->mailable->subject;

        return is_string($subject) && $subject !== '' ? $subject : null;
    }
}
