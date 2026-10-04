# Changelog

All notable changes to `laranail/barua` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2026-09-28

Responsive, un-styled Blade email components and a fluent mail builder for Laravel. This version
brings the package into the laranail family conventions; the moving `v0.1.0` tag carries it. IDs in
brackets refer to the adoption audit. Upgrade steps are in `docs/upgrading.md`.

### Changed

- PHP `^8.4.1 || ^8.5` and Laravel `^13.0` (was PHP 8.1, Laravel 9 to 11).
- The service provider is built on `laranail/package-tools`.
- Names carry the vendor: views and translations `laranail/barua::`, components
  `<x-laranail-barua::text />` (was `<x-laranail-barua-text>`), route names `laranail-barua.debug.*`,
  environment variables `LARANAIL_BARUA_*` (were `BARUA_*`). The global `Barua` alias is gone.
- Messages send when sent and queue when queued; `MailBase` no longer implements `ShouldQueue`,
  which queued every message [B7].
- The debug pages register only in dev mode **and** the `local` environment, look nothing up until a
  page is requested, and send only with `?send=1` [B4].
- `stylesheets` and linked stylesheets are inlined as before, but a `<link>` is followed only to a
  `.css` file inside the public directory; `inline_css` switches the inliner off [B11].
- The listeners log the mailable class, the recipient count and the error, never the template data,
  which carried reset and verification links [B10].

### Fixed

- Every component and bundled template threw "No hint path defined": they resolved `barua::` views
  while the views were registered under another name [B3, B14].
- Three of the four bundled templates were empty files; `verify_email`, `forgot_password` and
  `payment_confirmation` now have content, on a shared layout built from the components [B17].
- Attachments: each call replaced the last, and the list was then iterated as strings [B5]; the
  size-limit and MIME-type helpers returned config strings through `bool`/`array` return types [B6].
- A message without a subject, or a plain Laravel mailable, no longer throws in `MailSender` [B8, B18].
- Sending disabled dispatches `SendMailDisabled` instead of `SentMailEvent` [B8].
- `MailBuilder::getView()` before `setView()` returned an error instead of null [B9].
- Heading margins: an integer margin was a TypeError, and any margin broke rendering [B19].
- `<x-laranail-barua::link>` rendered `target` glued to `style`, which is invalid HTML [B20].

### Removed

- The provider's rebinding of the container's `mailer` to an undefined class built on
  `swift.mailer` [B1], and `Helpers::updateNamespaceInDirectory()`, which rewrote files in the
  application's `app/Mail/Messages` on every console boot [B2].
- The `barua_templates` migration, which ran in every application and which nothing used [B12].
- Empty stubs: `Jobs\SendEmailJob`, `Notifications\MailNotification`,
  `Notifications\TextNotification`, and the unused `EventServiceProvider`.
- The unused `pelago/emogrifier` and `jamesbwi/blade-svg` dependencies [B16].

### Internal history (not published)

The first `v0.1.0` (2026-09-01) described itself as adding a `Barua` facade and builder, CSS inlining
through `pelago/emogrifier`, events and jobs for queued delivery, and SVG support through
`jamesbwi/blade-svg`. The facade, builder and events existed; the inlining never used emogrifier,
the job was empty, and the SVG package was never called.

[Unreleased]: https://github.com/laranail/barua/compare/v0.1.0...HEAD
