# CSS inlining

`Simtabi\Laranail\Barua\Plugins\CssInlinerPlugin` inlines CSS into outgoing HTML mail, because most mail clients ignore `<style>` blocks and stylesheet links.

It listens to Laravel's `MessageSending` event, so it applies to **every** HTML message the application sends while `laranail.barua.inline_css` is on (the default), not only barua's. Turn it off with `LARANAIL_BARUA_INLINE_CSS=false`.

## Where the CSS comes from

1. **`laranail.barua.stylesheets`**: absolute paths, inlined into every message.

   ```php
   'stylesheets' => [
       public_path('css/mail.css'),
   ],
   ```

2. **`<link rel="stylesheet">` in the message**: removed from the markup, and its file inlined, **only** when the href names a `.css` file inside the public directory (`/css/mail.css`, or the same path absolute). Anything else, a URL, a path outside `public/`, a `../` escape, is dropped and logged at warning.

That restriction is deliberate. The inliner used to pass any href to `file_get_contents()`, so a template, or data rendered into one, could inline a server file into an email or make the server fetch a URL.

## When it fails

A message whose CSS cannot be inlined is sent without it, and the reason is logged at warning. It is never held back.

---

[← Docs index](../../README.md#documentation)
