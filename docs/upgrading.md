# Upgrading

What changed when barua joined the laranail family conventions, and what to do about each change.

## To the family conventions (0.1)

| Before | Now | What to do |
|---|---|---|
| PHP 8.1, Laravel 9 to 11 | PHP `^8.4.1 \|\| ^8.5`, Laravel `^13.0` | Upgrade the application first. |
| `BARUA_ENABLE_DEV_MODE`, `BARUA_ENABLE_ERROR_THROWING`, `BARUA_MAX_FILE_SIZE`, `BARUA_ALLOWED_MIME_TYPES`, `BARUA_ENABLE_SEND_MAIL` | `LARANAIL_BARUA_DEV_MODE`, `..._THROW_ERRORS`, `..._MAX_FILE_SIZE`, `..._ALLOWED_MIME_TYPES`, `..._SEND_MAIL` | Rename the variables in `.env`. |
| `<x-laranail-barua-text>` | `<x-laranail-barua::text>` | Replace `x-laranail-barua-` with `x-laranail-barua::` in your templates. |
| views `laranail-barua::` / `barua::` | `laranail/barua::` | Update `view()` calls and published view paths (`resources/views/vendor/laranail/barua`). |
| route names `laranail.barua.debug.*` | `laranail-barua.debug.*` | Update any `route()` calls. |
| a global `Barua` alias | none | Import `Simtabi\Laranail\Barua\Facades\BaruaFacade`. |
| `MailBase` implemented `ShouldQueue`, so every message queued | sends when you send, queues when you queue | Use `sendEmail(queued: true)` or `Mail::queue()` where you relied on queueing. |
| a `barua_templates` migration ran in the application | no migration | Nothing reads that table; drop it when convenient. |
| `SendEmailJob`, `MailNotification`, `TextNotification` | removed | They were empty stubs. |
| `Helpers::updateNamespaceInDirectory()` | removed | It rewrote files in `app/Mail/Messages` on every console boot. |

---

[← Docs index](../README.md#documentation)
