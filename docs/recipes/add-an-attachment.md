# Add an attachment

Attach an invoice PDF, with a size limit for this one file.

```php
$builder->setAttachments(
    path: storage_path("invoices/{$invoice->number}.pdf"),
    name: 'invoice.pdf',
    maxFileSize: '5MB',
);

if ($errors->getErrors('attachments') !== []) {
    // skipped: missing, too large, or a MIME type outside laranail.barua.allowed_mime_types
}
```

Call `setAttachments()` once per file. Limits and allowed types: [Mail builder](../tools/mail-builder.md#attachments).

---

[← Docs index](../../README.md#documentation)
