# Getting started

Build an email from barua's components, send it with the mail builder, and preview the bundled templates.

## Write an email with the components

Every component is a Blade tag under the `laranail-barua` prefix. They render table-based markup that holds together in mail clients, and they are un-styled: pass `style` (or any attribute) and it is merged in.

```blade
{{-- resources/views/emails/receipt.blade.php --}}
<x-laranail-barua::html lang="en">
    <x-laranail-barua::head>
        <title>Your receipt</title>
    </x-laranail-barua::head>
    <x-laranail-barua::body style="background-color: #f6f9fc;">
        <x-laranail-barua::container style="background-color: #ffffff; padding: 32px;">
            <x-laranail-barua::heading as="h1" mb="16">Thanks, {{ $name }}</x-laranail-barua::heading>
            <x-laranail-barua::text>Your order {{ $orderId }} is on its way.</x-laranail-barua::text>
            <x-laranail-barua::link href="{{ $trackingUrl }}">Track it</x-laranail-barua::link>
        </x-laranail-barua::container>
    </x-laranail-barua::body>
</x-laranail-barua::html>
```

Use the view from any Laravel mailable, or through barua's builder below. The full list is in [Components](tools/components.md).

## Send it

The builders collect the recipients, data and settings; `MailSender` renders the view and hands it to Laravel's mailer.

```php
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Services\MailSender;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;

$errors  = new ErrorBuilder();
$data    = (new DataBuilder())->setData([
    'name'              => $user->name,
    'companyName'       => 'Acme',
    'verification_link' => $verificationUrl,
]);
$builder = (new MailBuilder(errorBuilder: $errors))->setTo($user->email, $user->name);

$mail = new WelcomeUser(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

(new MailSender(mailable: $mail, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors))
    ->sendEmail(queued: true);

$errors->getErrors(); // anything that went wrong, keyed by where
```

## Preview the bundled templates

Set `LARANAIL_BARUA_DEV_MODE=true` in a `local` environment and open `/barua/debug`. See [Debug pages](tools/debug-pages.md).

---

[← Docs index](../README.md#documentation)
