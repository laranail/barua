# Configuration

Every key under `laranail.barua`, what it does, and the environment variable behind it.

Publish the file to change it: `php artisan vendor:publish --tag=laranail::barua-config` writes `config/laranail/barua.php`. Read values as `config('laranail.barua.<key>')`.

| Key | Env | Default | What it does |
|---|---|---|---|
| `dev_mode` | `LARANAIL_BARUA_DEV_MODE` | `false` | Registers the [debug pages](tools/debug-pages.md). They also require the `local` environment. |
| `user_class` | | `App\Models\User` | The Eloquent model the debug pages send a preview to (its first row). |
| `throw_errors` | `LARANAIL_BARUA_THROW_ERRORS` | `false` | Throw a `BaruaException` on the first builder error instead of logging a warning and carrying on. |
| `enable_send_mail` | `LARANAIL_BARUA_SEND_MAIL` | `true` | Off, `MailSender` builds messages but does not send them, and dispatches `SendMailDisabled`. |
| `max_file_size` | `LARANAIL_BARUA_MAX_FILE_SIZE` | `12MB` | The largest attachment accepted: bytes, or a size such as `5MB`. |
| `allowed_mime_types` | `LARANAIL_BARUA_ALLOWED_MIME_TYPES` | pdf, jpeg, png | The attachment types accepted. The env variable is a comma-separated list. |
| `sender.email` / `sender.name` | `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME` | | The from address when the builder sets none. |
| `inline_css` | `LARANAIL_BARUA_INLINE_CSS` | `true` | Inline CSS into outgoing HTML mail. See [CSS inlining](tools/css-inliner.md). |
| `stylesheets` | | `[]` | Absolute paths of stylesheets inlined into every HTML message. |

```dotenv
LARANAIL_BARUA_DEV_MODE=true
LARANAIL_BARUA_MAX_FILE_SIZE=5MB
LARANAIL_BARUA_ALLOWED_MIME_TYPES="application/pdf,image/png"
```

The environment variables were renamed from `BARUA_*` when the package joined the family conventions; see [Upgrading](upgrading.md).

---

[← Docs index](../README.md#documentation)
