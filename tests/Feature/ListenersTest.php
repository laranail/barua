<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Illuminate\Log\Events\MessageLogged;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Events\SentMailEvent;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\ForgotPassword;

it('never writes template data to the log (B10)', function (string $event): void {
    $logged = [];
    Log::listen(static function (MessageLogged $message) use (&$logged): void {
        $logged[] = $message->message . ' ' . json_encode($message->context);
    });

    $errors = new ErrorBuilder;
    $data = (new DataBuilder)->setData(['reset_link' => 'https://example.com/reset/secret-token']);
    $builder = new MailBuilder($errors)->setTo('ada@example.com');
    $mail = new ForgotPassword(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

    event($event === FailedMailEvent::class
        ? new FailedMailEvent($builder, $data, $mail, $errors, new RuntimeException('smtp down'))
        : new SentMailEvent($builder, $data, $mail, $errors));

    expect($logged)->not->toBeEmpty()
        ->and(implode("\n", $logged))->not->toContain('secret-token')->not->toContain('ada@example.com');
})->with([SentMailEvent::class, FailedMailEvent::class]);
