# Installation

Requirements, the Composer setup the laranail family needs, and what to publish.

## Requirements

- PHP `^8.4.1 || ^8.5`
- Laravel `^13.0`
- The `dom`, `fileinfo` and `libxml` PHP extensions

## Install

The laranail family resolves its packages through their Git repositories, not Packagist. Add the repositories to your application's `composer.json`:

```json
"repositories": [
    { "type": "vcs", "url": "https://github.com/laranail/barua" },
    { "type": "vcs", "url": "https://github.com/laranail/package-tools" }
]
```

Then require the package:

```bash
composer require laranail/barua
```

The service provider is discovered automatically. There is no global `Barua` alias; import `Simtabi\Laranail\Barua\Facades\BaruaFacade` where you need it.

## Publish what you want to change

Nothing needs publishing to use the package. Each tag copies one part into your application, where your copy takes precedence:

| Tag | What it copies | Where |
|---|---|---|
| `laranail::barua-config` | `config/barua.php` | `config/laranail/barua.php` |
| `laranail::barua-views` | the component and message views | `resources/views/vendor/laranail/barua` |
| `laranail::barua-translations` | the language files | `lang/vendor/laranail/barua` |
| `laranail::barua-assets` | the debug pages' CSS, JS and images | `public/vendor/laranail/barua` |

```bash
php artisan vendor:publish --tag=laranail::barua-config
```

Running `php artisan vendor:publish` with no options lists every tag, barua's among them, and asks which to publish.

## Mail

barua sends through your application's own mail configuration: the mailer, credentials and `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME` in your `.env`. It does not replace or wrap the mailer.

---

[← Docs index](../README.md#documentation)
