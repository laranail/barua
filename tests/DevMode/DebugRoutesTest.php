<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;

it('registers the debug routes in dev mode on local', function (): void {
    expect(Route::has('laranail-barua.debug.home'))->toBeTrue()
        ->and(Route::has('laranail-barua.debug.welcome_user'))->toBeTrue();
});

it('renders the debug home page', function (): void {
    $this->get(route('laranail-barua.debug.home'))->assertOk()->assertSee('Barua');
});

it('previews a template without sending it, and with no users in the database (B4)', function (): void {
    Mail::fake();

    $this->get(route('laranail-barua.debug.welcome_user'))->assertOk()->assertSee('Barua');

    Mail::assertNothingOutgoing();
});

it('sends only when asked to', function (): void {
    Mail::fake();

    $this->get(route('laranail-barua.debug.welcome_user', ['send' => 1]))->assertOk();

    Mail::assertSent(WelcomeUser::class, static fn (WelcomeUser $mail): bool => $mail->hasTo('sender@example.com'));
});
