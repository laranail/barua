# Mail sender

`Simtabi\Laranail\Barua\Services\MailSender` renders a message's view and hands it to Laravel's mailer, once per recipient.

```php
$sender = new MailSender(mailable: $mail, mailBuilder: $builder, dataBuilder: $data, errorBuilder: $errors);

$sender->sendEmail();                           // send now
$sender->sendEmail(queued: true);               // queue
$sender->sendEmail(queued: true, delay: 300);   // queue, 5 minutes later
```

`sendEmail()` returns the rendered mailable, or `false` when the view could not be added (the reason is on the error builder). The mailable can be any Laravel `Mailable`; barua's own subclass `MailBase`.

## What it does, in order

1. Takes the subject from the builder, else the mailable; replaces `:key` placeholders from the data.
2. Sets the from address from the builder, else `laranail.barua.sender`.
3. Renders the builder's view as HTML, Markdown or text, and attaches the builder's files.
4. If sending is enabled, sends (or queues) to each `to`, `cc` and `bcc` recipient and dispatches `SentMailEvent`; a failure dispatches `FailedMailEvent` and is recorded under `send-mail`.
5. If sending is disabled, dispatches `SendMailDisabled` and sends nothing.

## Turning sending off

`laranail.barua.enable_send_mail` off disables sending everywhere; `$sender->setSendMail(false)` for one message. Either way the message is still built and returned, which is what the debug pages use to render a preview.

## `Barua` facade

`BaruaFacade::mailer($mailable, $mailBuilder, $dataBuilder, $errorBuilder, queued: false, delay: null)` does the same in one call. `BaruaFacade::asset('img/logo.svg')` returns the URL of a published debug asset.

---

[← Docs index](../../README.md#documentation)
