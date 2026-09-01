# Contributing

Thanks for helping improve `laranail/barua`.

## Getting set up

```bash
composer install
composer lint
composer test:stan
composer test
composer ci
```

Requires PHP `^8.1|^8.2|^8.3`.

## What must pass

- **Style**: `composer pint-fix` applies the Laravel Pint preset; `composer lint` checks it.
- **Static analysis**: `composer test:stan` (PHPStan).
- **Tests**: `composer test` (Pest).
- **Everything**: `composer ci` runs the lot as CI does.

## About this package

**This package has no tests yet.** `composer test` runs Pest against an empty suite, so a green
run proves nothing. A pull request that adds behaviour should add the first tests for it.

It is also the family's outlier on versions: `illuminate/*` is still `^9.0|^10.0|^11.0` and PHP
`^8.1`, where every other laranail package is on `^13.0` and PHP `^8.4.1`. Raising that is a
breaking change and its own pull request, not something to slip into an unrelated one.

## Pull requests

Changes reach `main` through a pull request. CI runs on the pull request, not on a push to a
branch, so a green tick means the change was gated rather than reported on after the fact.

- Tests added or updated for new behaviour, where the package has a suite
- `composer lint` clean
- `CHANGELOG.md` updated under `## Unreleased` for anything user-facing
- Commits follow [Conventional Commits](https://www.conventionalcommits.org/)
- No AI attribution anywhere: not in commits, PR titles or bodies, code comments or docs

## Security

See [`SECURITY.md`](SECURITY.md). Do not open a public issue for a vulnerability.
