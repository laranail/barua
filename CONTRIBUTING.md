# Contributing

Where this file is silent, the [laranail contributing guide](https://github.com/laranail/.github/blob/HEAD/CONTRIBUTING.md) applies.

Thanks for helping improve `laranail/barua`.

## Getting set up

Requires PHP `^8.4.1 || ^8.5`.

```bash
composer update
composer lint
composer test
```

`composer lint` runs Pint (the family's shared preset), PHPStan and Rector; `composer test` runs Pest.
The debug pages' assets are built with `npm install && npm run build`, and `public/assets` is committed.

## What must pass

- **Style**: `composer pint`; `composer pint-fix` applies it.
- **Static analysis**: `composer phpstan`, with no baseline.
- **Refactors**: `composer rector` reports none pending.
- **Tests**: `composer test`. A change in behaviour comes with a test that fails without it.

## Pull requests

Changes reach `main` through a pull request, and `main` requires CI to pass. CI runs on the pull
request: the suite on PHP 8.4 and 8.5 with lowest and stable dependencies and on Windows, static
analysis, and the security audit.

- `CHANGELOG.md` updated under `## [Unreleased]` for anything user-facing
- No AI attribution anywhere: not in commits, PR titles or bodies, code comments or docs

## Security

See [`SECURITY.md`](SECURITY.md). Do not open a public issue for a vulnerability.
