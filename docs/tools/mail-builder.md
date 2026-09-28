# Mail builder

The three builders that describe a message: `MailBuilder` for who and what, `DataBuilder` for the view's data, `ErrorBuilder` for what went wrong.

## `MailBuilder`

`Simtabi\Laranail\Barua\Builders\MailBuilder`, constructed with an `ErrorBuilder`.

| Method | What it sets |
|---|---|
| `setTo(email, name)`, `setCc(...)`, `setBcc(...)` | a recipient; call once per address |
| `setSender(email, name)` | the from address; the configured sender when unset |
| `setSubject(subject)` | the subject; `:key` placeholders are replaced from the data. Falls back to the mailable's own subject. |
| `setView(view)` | a bundled message (`onboarding.welcome_user`), or any view with `setView($view, customPath: true)` |
| `isHtmlViewType()`, `isMarkdownViewType()`, `isTextViewType()` | how the view is rendered; HTML by default |
| `setAttachments(path, name, mime, maxFileSize)` | an attachment; call once per file. See below. |

### Attachments

Each call appends one file. A file is skipped, and the reason recorded on the error builder under `attachments`, when it does not exist, is larger than `maxFileSize` (or `laranail.barua.max_file_size`), or has a MIME type outside `laranail.barua.allowed_mime_types`.

```php
$builder
    ->setAttachments(storage_path('invoices/407878364.pdf'), 'invoice.pdf')
    ->setAttachments(storage_path('terms.pdf'), maxFileSize: '2MB');
```

## `DataBuilder`

`setData(array $data, ?string $keyPath = null)` merges data for the view; with a key path it nests it:

```php
$data = (new DataBuilder())
    ->setData(['name' => 'Ada', 'companyName' => 'Acme'])
    ->setData(['street' => '5th Ave', 'number' => '101'], 'user.details.address');
```

`getData()` returns everything the view receives. `getData('user.details')` returns only the entries of that branch named by `setVariables('[street], [number]')`, and an empty array when no variables are set.

## `ErrorBuilder`

`getErrors()` returns every error keyed by where it happened (`view`, `attachments`, `send-mail`, `send-mail-subject`, ...); `getErrors('attachments')` one list. Each error is also logged at warning. With `laranail.barua.throw_errors` on, the first error throws a `BaruaException` instead.

---

[← Docs index](../../README.md#documentation)
