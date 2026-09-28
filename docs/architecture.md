# Architecture

How barua is put together: components for the markup, three builders for the message, one sender, and events around the send.

## The parts

| Part | Class | Job |
|---|---|---|
| Components | `View\Components\*` | 14 Blade components under `<x-laranail-barua::...>` that render mail-safe table markup. |
| Bundled messages | `Mail\Messages\*` on `Mail\MailBase` | Four ready-made mailables and their views. |
| Mail builder | `Builders\MailBuilder` | Recipients (to, cc, bcc), sender, subject, view, view type, attachments. |
| Data builder | `Builders\DataBuilder` | The data the view renders, including nested keys (`user.details.address`). |
| Error builder | `Builders\ErrorBuilder` | Collects what went wrong, keyed by where; throws instead when `throw_errors` is on. |
| Sender | `Services\MailSender` | Renders the view into the mailable and hands it to Laravel's mailer, per recipient. |
| Events | `SentMailEvent`, `FailedMailEvent`, `SendMailDisabled` | What happened to each send; the bundled listeners log it. |
| CSS inliner | `Plugins\CssInlinerPlugin` | Inlines stylesheets into outgoing HTML mail on `MessageSending`. |

## Why builders and an error collector?

A message is assembled in several places: recipients from one, data from another, attachments from a third. The builders let each part be set where it is known, and the error builder lets a problem in one (an attachment that is too large) be reported without stopping the rest. Set `throw_errors` when you would rather fail fast.

## Why does barua not replace the mailer?

Earlier versions bound the container's `mailer` to their own class. A package that owns something the application relies on for every email is a package that can break all of them, and that one could: it depended on `swift.mailer`, which Laravel removed in 9.0. barua now only renders messages and passes them to whatever mailer the application configured.

## Why are the debug pages local-only?

They send real mail from GET requests, to a real user. So they need both the `dev_mode` switch and the `local` environment, and they are decided at boot, so a cached route file keeps whichever answer was in force when it was built.

## Naming

Every name barua registers carries the vendor, as the laranail family requires: views and translations `laranail/barua::`, Blade components `laranail-barua::` (a Blade tag cannot hold a slash), config `laranail.barua`, publish tags `laranail::barua-*`, routes `laranail-barua.debug.*`. `tests/Feature/NamingConventionTest.php` checks them against the live registries.

## Lineage

barua's components and approach draw on:

- [resendlabs/react-email](https://github.com/resendlabs/react-email), by Bu Kinoshita and Zeno Rocha
- [damilaredev/laravel-email](https://github.com/damilaredev/laravel-email)
- [fedeisas/laravel-mail-css-inliner](https://github.com/fedeisas/laravel-mail-css-inliner) and [bradietilley/laravel-css-inliner](https://github.com/bradietilley/laravel-css-inliner), for the CSS inliner
- [spatie/laravel-database-mail-templates](https://github.com/spatie/laravel-database-mail-templates)

---

[← Docs index](../README.md#documentation)
