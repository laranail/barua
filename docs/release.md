# Release

How versions are cut and how consumers pick them up.

## Versioning

barua follows semantic versioning and, while it is pre-1.0, the laranail single-moving-tag convention: there is one tag, `v0.1.0`, and each change moves it rather than cutting a new one. `main` carries a `dev-main → 0.1.x-dev` branch alias, so a dev checkout still satisfies `^0.1`.

```json
"laranail/barua": "^0.1"
```

Composer caches a dist archive per tag name, so after the tag moves, run `composer clear-cache` before `composer update`, or you install the archive you had before.

## Cutting a release

1. **Rebuild the debug assets** if `resources/assets` changed. `public/assets` is committed, because consumers install through Composer and never run npm:

   ```bash
   npm install --no-audit --no-fund
   npm run build
   ```

2. **Land the change through a pull request.** CI runs the tests (PHP 8.4 and 8.5, lowest and stable dependencies, and Windows), static analysis and the security audit, and `main` requires them to pass.

3. **Move the tag to the merged commit**, after the branch has landed:

   ```bash
   git switch main && git pull --ff-only
   git tag -f v0.1.0 && git push --force origin v0.1.0
   ```

4. The `release.yml` workflow publishes the GitHub release, with this version's CHANGELOG section as its notes and a CycloneDX SBOM attached.

---

[← Docs index](../README.md#documentation)
