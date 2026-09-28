# Events

Three events around each send, in `Simtabi\Laranail\Barua\Events`, each carrying the builders and the mailable.

| Event | When | Extra |
|---|---|---|
| `SentMailEvent` | a message was handed to the mailer (sent or queued) | |
| `FailedMailEvent` | handing it over threw | `exception` |
| `SendMailDisabled` | sending is off, so nothing was sent | |

All three expose `mailBuilder`, `dataBuilder`, `mailable` and `errorBuilder` as readonly properties.

## The bundled listeners

`LogSentMail`, `LogFailedMail` and `LogSendMailDisabled` log the mailable class, the number of recipients and, on failure, the error. They never log the template data or the addresses: that data carries password-reset and verification links and personal details.

## Your own listener

```php
use Illuminate\Support\Facades\Event;
use Simtabi\Laranail\Barua\Events\FailedMailEvent;

Event::listen(function (FailedMailEvent $event): void {
    report($event->exception);
});
```

---

[← Docs index](../../README.md#documentation)
