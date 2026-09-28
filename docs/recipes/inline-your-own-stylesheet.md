# Inline your own stylesheet

Style every outgoing HTML message from one CSS file.

```php
// config/laranail/barua.php
'stylesheets' => [
    public_path('css/mail.css'),
],
```

Or link it from a template, which works only for a `.css` file inside `public/`:

```blade
<link rel="stylesheet" href="/css/mail.css">
```

How it works and what is refused: [CSS inlining](../tools/css-inliner.md).

---

[← Docs index](../../README.md#documentation)
