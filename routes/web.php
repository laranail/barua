<?php declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Mail\DebugEmail;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\ForgotPassword;
use Simtabi\Laranail\Barua\Mail\Messages\Marketing\PaymentConfirmation;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\VerifyEmail;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Support\Helpers;

if (Helpers::isDevModeEnable()) {

    // get user model
    $user = Helpers::getUserModel();
    /** @var Model|string $user */
    $user = $user::first();

    // init mail builder classes
    $errorBuilder = new ErrorBuilder();
    $dataBuilder  = (new DataBuilder())->setVariables('[name], [email], [phone], [package],[price], [method],[note]');
    $mailBuilder  = (new MailBuilder(errorBuilder: $errorBuilder))->setTo(email: $user->email, name: $user->name);

    // queued and delay configurations
    $queued = false;
    $delay  = null;
    $data   = [
        'name'        => 'Mogaka',
        'serviceName' => 'Barua',
        'productName' => 'Barua',
        'companyName' => 'Simtabi',
        'productOrService' => 'Barua Email Kit',
        'solutionOrOffer'  => 'Barua Email Kit',
        'unsubscribeLink'  => 'https://simtabi.com',
    ];

    Route::prefix('barua/debug')->name('barua.debug.')->group(function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {

        Route::get('/', function () use ($data) {
            return view(Helpers::getViewPath('home'), array_merge([
                'title' => 'Barua Debug',
                'description' => 'This is a debug page for Barua package',
            ], $data));
        })->name('home');


        Route::prefix('preview')->group(function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data){

            // Route when template is not provided
            Route::get('/', function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {
                return redirect()->route('barua.debug.home');
            });

            // Route when template is provided
            Route::get('/{template}/{send?}', function (Request $request, $template) use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {

                // Retrieve the 'send' query parameter and set default as false if not provided
                $sendMail = $request->query('send', 'false');

                if (!empty($template)) {
                    $template = trim($template);
                }

                if (!empty($sendMail)) {
                    $sendMail = filter_var(trim($sendMail), FILTER_VALIDATE_BOOLEAN);
                }

                // Create the mailable instance
                $mailable = (new DebugEmail(
                    mailBuilder: $mailBuilder,
                    dataBuilder: $dataBuilder->setData(data: $data),
                    errorBuilder: $errorBuilder
                ))->setView(view: $template);

                return (new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder))->setSendMail($sendMail)->sendEmail(queued: $queued, delay: $delay);

            })->where('template', '[a-zA-Z0-9-_]+')->where('send', '1|0|true|false')->name('preview');
        });


        Route::get('welcome_user', function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {
            $mailable = new WelcomeUser(
                mailBuilder: $mailBuilder,
                dataBuilder: $dataBuilder
                    ->setData(array_merge([
                        'verification_link' => 'https://simtabi.com',
                    ], $data))
                    ->setData(['street' => '5th Ave', 'number' => '101'], 'user.details.address')
                    ->setData(['phone' => '123-456-7890'], 'user.details.contact'),
                errorBuilder: $errorBuilder
            );

            return (new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder))->sendEmail(queued: $queued, delay: $delay);

        })->name('welcome_user');

        Route::get('verify_email', function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {
            $mailable = new VerifyEmail(
                mailBuilder: $mailBuilder,
                dataBuilder: $dataBuilder->setData(array_merge([
                    'verification_link' => 'https://simtabi.com',
                ], $data)),
                errorBuilder: $errorBuilder
            );

            return (new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder))->sendEmail(queued: $queued, delay: $delay);
        })->name('verify_email');

        Route::get('forgot_password', function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {
            $mailable = new ForgotPassword(
                mailBuilder: $mailBuilder,
                dataBuilder: $dataBuilder->setData(array_merge([
                    'reset_link' => 'https://simtabi.com',
                ], $data)),
                errorBuilder: $errorBuilder
            );

            return (new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder))->sendEmail(queued: $queued, delay: $delay);
        })->name('forgot_password');

        Route::get('payment_confirmation', function () use ($mailBuilder, $dataBuilder, $errorBuilder, $queued, $delay, $data) {
            $mailable = new PaymentConfirmation(
                mailBuilder: $mailBuilder,
                dataBuilder: $dataBuilder->setData(array_merge([
                    'invoice_id'    => '407878364',
                    'invoice_total' => '160.69',
                    'download_link' => 'https://simtabi.com',
                ], $data)),
                errorBuilder: $errorBuilder
            );

            return (new MailSender(mailable: $mailable, mailBuilder: $mailBuilder, dataBuilder: $dataBuilder, errorBuilder: $errorBuilder))->sendEmail(queued: $queued, delay: $delay);
        })->name('payment_confirmation');

    });

}
