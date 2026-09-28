# Preview templates locally

Look at the bundled templates in a browser without sending anything.

```dotenv
APP_ENV=local
LARANAIL_BARUA_DEV_MODE=true
```

```bash
php artisan vendor:publish --tag=laranail::barua-assets
php artisan route:clear
```

Open `/barua/debug`. Add `?send=1` to a template page to send it to yourself. Details: [Debug pages](../tools/debug-pages.md).

---

[← Docs index](../../README.md#documentation)
