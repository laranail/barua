# Debug pages

Preview pages for the bundled templates, at `/barua/debug`.

## Turning them on

Both are required:

- `LARANAIL_BARUA_DEV_MODE=true`
- the application environment is `local`

The routes are decided when the application boots, so after changing either, clear a cached route file with `php artisan route:clear`.

## The pages

| Route name | Path | Renders |
|---|---|---|
| `laranail-barua.debug.home` | `/barua/debug` | an index of the templates |
| `laranail-barua.debug.welcome_user` | `/barua/debug/welcome_user` | the welcome message |
| `laranail-barua.debug.verify_email` | `/barua/debug/verify_email` | email verification |
| `laranail-barua.debug.forgot_password` | `/barua/debug/forgot_password` | password reset |
| `laranail-barua.debug.payment_confirmation` | `/barua/debug/payment_confirmation` | payment confirmation |

Each renders its template with sample data. **Add `?send=1` to also send it**, to the first row of `laranail.barua.user_class`, or to `laranail.barua.sender` when there is none. Without it nothing is sent.

The page styles come from the package's published assets: `php artisan vendor:publish --tag=laranail::barua-assets`.

---

[← Docs index](../../README.md#documentation)
