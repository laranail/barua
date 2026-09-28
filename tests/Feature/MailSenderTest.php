<?php

declare(strict_types=1);

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Simtabi\Laranail\Barua\Mail\MailBase;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Events\SendMailDisabled;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\VerifyEmail;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\ForgotPassword;
use Simtabi\Laranail\Barua\Mail\Messages\Marketing\PaymentConfirmation;

/**
 * @param class-string<MailBase> $class
 *
 * @return array{0: MailSender, 1: MailBase, 2: ErrorBuilder}
 */
function baruaSender(string $class = WelcomeUser::class): array
{
    $errors = new ErrorBuilder;
    $data = (new DataBuilder)->setData(['serviceName' => 'Acme', 'companyName' => 'Acme', 'name' => 'Ada', 'reset_link' => 'https://example.com/reset/secret-token']);
    $builder = new MailBuilder(errorBuilder: $errors)->setTo('ada@example.com', 'Ada');
    $message = new $class(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

    return [new MailSender(mailable: $message, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors), $message, $errors];
}

it('renders every bundled template (B14)', function (string $class): void {
    [$sender, , $errors] = baruaSender($class);

    $mailable = $sender->setSendMail(false)->sendEmail();

    expect($errors->getErrors('view'))->toBeEmpty()
        ->and($mailable)->not->toBeFalse()
        ->and($mailable->render())->toContain('Acme');
})->with([WelcomeUser::class, VerifyEmail::class, ForgotPassword::class, PaymentConfirmation::class]);

it('sends immediately when not asked to queue (B7)', function (): void {
    Mail::fake();

    [$sender] = baruaSender();
    $sender->sendEmail(queued: false);

    Mail::assertSent(WelcomeUser::class, static fn (WelcomeUser $mail): bool => $mail->hasTo('ada@example.com'));
    Mail::assertNothingQueued();
});

it('queues when asked to', function (): void {
    Mail::fake();

    [$sender] = baruaSender();
    $sender->sendEmail(queued: true);

    Mail::assertQueued(WelcomeUser::class);
    Mail::assertNothingSent();
});

it('replaces placeholders in the subject', function (): void {
    Mail::fake();

    [$sender] = baruaSender();
    $sender->sendEmail();

    Mail::assertSent(WelcomeUser::class, static fn (WelcomeUser $mail): bool => $mail->subject === "Welcome to Acme - Let's Get Started!");
});

it('dispatches SendMailDisabled, not SentMailEvent, when sending is off (B8)', function (): void {
    Mail::fake();
    Event::fake([SendMailDisabled::class, SentMailEvent::class]);

    [$sender] = baruaSender();
    $sender->setSendMail(false)->sendEmail();

    Event::assertDispatched(SendMailDisabled::class);
    Event::assertNotDispatched(SentMailEvent::class);
    Mail::assertNothingOutgoing();
});

it('survives a message with no subject (B8)', function (): void {
    Mail::fake();

    $errors = new ErrorBuilder;
    $data = new DataBuilder;
    $builder = new MailBuilder(errorBuilder: $errors)->setTo('ada@example.com')->setView('onboarding.welcome_user');
    $message = new MailBase(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors, className: MailBase::class);

    new MailSender(mailable: $message, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors)->sendEmail();

    expect($errors->getErrors('send-mail-subject'))->toHaveCount(1);
});

it('sends a plain Laravel mailable, reading its own subject (B18)', function (): void {
    Mail::fake();

    $plain = new class extends Mailable
    {
        public $subject = 'Hello :name';
    };

    $errors = new ErrorBuilder;
    $data = (new DataBuilder)->setData(['name' => 'Ada']);
    $builder = new MailBuilder(errorBuilder: $errors)->setTo('ada@example.com')->setView('onboarding.welcome_user');

    new MailSender(mailable: $plain, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors)->sendEmail();

    Mail::assertSent($plain::class, static fn ($mail): bool => $mail->subject === 'Hello Ada');
});
