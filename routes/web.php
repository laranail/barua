<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\View\Factory;
use Simtabi\Laranail\Barua\Support\Helpers;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\VerifyEmail;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\ForgotPassword;
use Simtabi\Laranail\Barua\Mail\Messages\Marketing\PaymentConfirmation;

/*
| Debug pages for the bundled templates. Loaded by the provider only when
| `laranail.barua.dev_mode` is on and the environment is `local`.
|
| Each page renders its template. It sends it only with `?send=1`, to the first
| row of `laranail.barua.user_class`, or to the configured sender when there is
| none. Nothing is queried until a page is requested.
*/

$sample = [
    'name'             => 'Mogaka',
    'serviceName'      => 'Barua',
    'productName'      => 'Barua',
    'companyName'      => 'Simtabi',
    'productOrService' => 'Barua Email Kit',
    'solutionOrOffer'  => 'Barua Email Kit',
    'unsubscribeLink'  => 'https://example.com/unsubscribe',
];

$templates = [
    'welcome_user'         => [WelcomeUser::class, ['verification_link' => 'https://example.com/verify']],
    'verify_email'         => [VerifyEmail::class, ['verification_link' => 'https://example.com/verify']],
    'forgot_password'      => [ForgotPassword::class, ['reset_link' => 'https://example.com/reset']],
    'payment_confirmation' => [PaymentConfirmation::class, ['invoice_id' => '407878364', 'invoice_total' => '160.69', 'download_link' => 'https://example.com/invoice']],
];

$preview = static function (Request $request, string $mailable, array $data): mixed {
    $recipient = Helpers::getSender();

    $userClass = Helpers::getUserModel();
    $user = $userClass::query()->first();

    $errorBuilder = new ErrorBuilder;
    $dataBuilder = (new DataBuilder)->setData($data);
    $mailBuilder = new MailBuilder(errorBuilder: $errorBuilder)->setTo(
        email: (string) ($user->email ?? $recipient->email),
        name: $user->name ?? $recipient->name,
    );

    $message = new $mailable(mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder);

    return new MailSender(mailable: $message, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder)
        ->setSendMail($request->boolean('send'))
        ->sendEmail();
};

Route::prefix('barua/debug')->name('laranail-barua.debug.')->group(static function () use ($sample, $templates, $preview): void {
    Route::get('/', static fn (): Factory|View => view(Helpers::getViewPath('home'), [
        'title'       => 'Barua Debug',
        'description' => 'Preview pages for the templates barua ships.',
        ...$sample,
    ]))->name('home');

    foreach ($templates as $name => [$mailable, $data]) {
        Route::get($name, static fn (Request $request): Mailable|false => $preview($request, $mailable, [...$sample, ...$data]))->name($name);
    }
});
