# Changelog

All notable changes to `laranail/barua` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-09-01

Effortlessly design and send responsive emails from customisable Blade components.

First tagged release. The package is pre-1.0, so the public surface is still
free to change; only the latest tag receives fixes.

### Added

- `Barua` facade and builder for composing an email from components.
- Blade component set, inlined for mail clients through `pelago/emogrifier`.
- Events, jobs and listeners for queued delivery.
- SVG support via `jamesbwi/blade-svg`.
