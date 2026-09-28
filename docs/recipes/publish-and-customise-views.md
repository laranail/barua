# Publish and customise the views

Change how a component or a bundled template looks.

```bash
php artisan vendor:publish --tag=laranail::barua-views
```

Edit your copies in `resources/views/vendor/laranail/barua`: `components/` for the components, `messages/` for the templates. Your copy takes precedence; a view you leave untouched keeps receiving updates only if you delete its published copy.

---

[← Docs index](../../README.md#documentation)
