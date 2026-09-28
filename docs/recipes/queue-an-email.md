# Queue an email

Queue a message instead of sending it during the request, optionally after a delay.

```php
$sender->sendEmail(queued: true);                           // on the default queue
$sender->sendEmail(queued: true, delay: now()->addMinutes(10));
```

Messages send immediately unless you ask for the queue. (Before the family conventions every barua message was queued; see [Upgrading](../upgrading.md).)

---

[← Docs index](../../README.md#documentation)
