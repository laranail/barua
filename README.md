# laranail/barua

[![Tests](https://github.com/laranail/barua/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/barua/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/barua/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/barua/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

`laranail/barua` is not on Packagist, so there is no registry-version badge to show; [Install](#install) covers the VCS route.

> Responsive, un-styled Blade email components and a fluent mail builder for Laravel.

Targets PHP `^8.4.1 || ^8.5` on Laravel `^13.0`. Built on `laranail/package-tools`.

## Install

Add the VCS repositories (the laranail family does not resolve through Packagist), then require the package:

```json
"repositories": [
    { "type": "vcs", "url": "https://github.com/laranail/barua" },
    { "type": "vcs", "url": "https://github.com/laranail/package-tools" }
]
```

```bash
composer require laranail/barua
```

```blade
<x-laranail-barua::text>Hello from barua.</x-laranail-barua::text>
```

## Quick start guide and usage

### Getting started

1. Nothing to register: the service provider is discovered automatically.
2. barua sends through your application's own mail configuration, so set the mailer and `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME` in `.env` as you would for any Laravel mail.
3. Optionally, publish the config to change a default:

   ```bash
   php artisan vendor:publish --tag=laranail::barua-config
   ```

### Usage

```blade
{{-- resources/views/emails/receipt.blade.php --}}
<x-laranail-barua::html lang="en">
    <x-laranail-barua::body style="background-color: #f6f9fc;">
        <x-laranail-barua::container style="background-color: #ffffff; padding: 32px;">
            <x-laranail-barua::heading as="h1" mb="16">Thanks, {{ $name }}</x-laranail-barua::heading>
            <x-laranail-barua::text>Your order {{ $orderId }} is on its way.</x-laranail-barua::text>
            <x-laranail-barua::link href="{{ $trackingUrl }}">Track it</x-laranail-barua::link>
        </x-laranail-barua::container>
    </x-laranail-barua::body>
</x-laranail-barua::html>
```

Send one of the bundled messages with the builders and `MailSender`:

```php
use Simtabi\Laranail\Barua\Builders\DataBuilder;
use Simtabi\Laranail\Barua\Builders\ErrorBuilder;
use Simtabi\Laranail\Barua\Builders\MailBuilder;
use Simtabi\Laranail\Barua\Mail\Messages\Onboarding\WelcomeUser;
use Simtabi\Laranail\Barua\Services\MailSender;

$errors  = new ErrorBuilder();
$data    = (new DataBuilder())->setData(['name' => $user->name, 'companyName' => 'Acme', 'verification_link' => $verificationUrl]);
$builder = (new MailBuilder(errorBuilder: $errors))->setTo($user->email, $user->name);
$mail    = new WelcomeUser(mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

(new MailSender(mailable: $mail, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors))
    ->sendEmail(queued: true);
```

The full walkthrough is in [Getting started](docs/getting-started.md); everything else is in the [documentation index](#documentation).

## <a name="documentation"></a>Documentation

Full documentation is at **[opensource.simtabi.com/documentation/laranail/barua](https://opensource.simtabi.com/documentation/laranail/barua/)**.

### Guides

- [Installation](docs/installation.md): requirements, VCS setup, publish tags
- [Getting started](docs/getting-started.md): write an email with the components and send it
- [Configuration](docs/configuration.md): every key under `laranail.barua`
- [Architecture](docs/architecture.md): the components, builders, sender and events, and why
- [Upgrading](docs/upgrading.md): what changed with the family conventions
- [Release](docs/release.md): how versions are cut and consumed

### Reference

- [Components](docs/tools/components.md) · [Mail builder](docs/tools/mail-builder.md) · [Mail sender](docs/tools/mail-sender.md)
- [Bundled templates](docs/tools/templates.md) · [Events](docs/tools/events.md) · [CSS inlining](docs/tools/css-inliner.md) · [Debug pages](docs/tools/debug-pages.md)

### Recipes

- [Send a bundled template](docs/recipes/send-a-bundled-template.md) · [Build a custom email](docs/recipes/build-a-custom-email.md) · [Add an attachment](docs/recipes/add-an-attachment.md)
- [Queue an email](docs/recipes/queue-an-email.md) · [Preview templates locally](docs/recipes/preview-templates-locally.md)
- [Publish and customise the views](docs/recipes/publish-and-customise-views.md) · [Inline your own stylesheet](docs/recipes/inline-your-own-stylesheet.md)

## Contributing & security

Issues and PRs are welcome; see [CONTRIBUTING.md](CONTRIBUTING.md). Report vulnerabilities privately per [SECURITY.md](SECURITY.md). Participation follows the laranail [Code of Conduct](https://github.com/laranail/.github/blob/main/CODE_OF_CONDUCT.md).

## License

MIT © Simtabi LLC. See [LICENSE](LICENSE).
