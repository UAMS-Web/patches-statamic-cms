Written against v1.1.0, and `cweagans/composer-patches` 2.0.0.

- [Composer looks for the package on Packagist](#composer-looks-for-the-package-on-packagist)
- [The package installs but no patch is applied](#the-package-installs-but-no-patch-is-applied)
- [`composer update` aborts with `The "vendor/..." file could not be downloaded`](#composer-update-aborts-with-the-vendor-file-could-not-be-downloaded)
- [A patch fails on a hash mismatch, or the old patch text is applied](#a-patch-fails-on-a-hash-mismatch-or-the-old-patch-text-is-applied)
- [Composer cannot resolve the package beside another `statamic/cms` version](#composer-cannot-resolve-the-package-beside-another-statamiccms-version)
- [Composer stays on an older package release](#composer-stays-on-an-older-package-release)
- [The patch files are not found under a custom `vendor-dir`](#the-patch-files-are-not-found-under-a-custom-vendor-dir)

## Composer looks for the package on Packagist

**Cause:** the consumer's root `composer.json` has no `vcs` entry for this repository. The package is not on Packagist, and Composer reads `repositories` only from the root project, so an entry in a dependency does not count.

**Fix:** add the entry before requiring the package:

```bash
composer config repositories.patches-statamic-cms vcs https://github.com/UAMS-Web/patches-statamic-cms.git
```

## The package installs but no patch is applied

**Cause:** an application required the package in `require-dev`. The plugin applies a dependency's patches only from `packages` in `composer.lock`, so a package in `packages-dev` is installed and silently not applied.

**Fix:** in an application, require it in `require`. A package that another repository installs is the exception: it requires this package with `--dev` and lists the patch files in its own root `patches.json`, as the [README](https://github.com/UAMS-Web/patches-statamic-cms#install) says.

## `composer update` aborts with `The "vendor/..." file could not be downloaded`

**Cause:** no `patches.lock.json` is committed. The plugin resolves the patch list before the package is installed, so the patch paths under `vendor/uams-web/patches-statamic-cms/` do not exist yet.

**Fix:** require the package with plugins off, then relock and reapply, and commit `patches.lock.json`. In a package, add `--dev` and list the patches in its root `patches.json` first; run `composer update --lock` where `composer.json` changed.

```bash
composer require --no-plugins uams-web/patches-statamic-cms:^1.0
composer patches-relock
composer patches-repatch
```

## A patch fails on a hash mismatch, or the old patch text is applied

**Cause:** the package moved to a release that changed a patch, but `patches.lock.json` was not relocked. The plugin reads the committed lock without checking it for freshness and fetches each patch against its locked `sha256`.

**Fix:** follow [Adopting a new release](Adopting-a-new-release.md).

## Composer cannot resolve the package beside another `statamic/cms` version

**Cause:** each release pins `statamic/cms` exactly; v1.1.0 pins `6.40.0`. That is deliberate: a version the patches were not written against fails at resolution rather than at patch time.

**Fix:** move to the package release that pins the `statamic/cms` version you need. Where none exists yet, the patches have not been cut against that version; [report it](https://github.com/UAMS-Web/patches-statamic-cms/issues).

## Composer stays on an older package release

**Cause:** the update was run without `--with-dependencies`, so `statamic/cms` was held at its locked version, and Composer chose the newest package release that pins that version without saying so. Another constraint in the consumer that holds `statamic/cms` back has the same effect.

**Fix:** `composer update uams-web/patches-statamic-cms --with-dependencies`, and check what else constrains `statamic/cms`.

## The patch files are not found under a custom `vendor-dir`

**Cause:** the patch paths are relative to the consumer's root and start `vendor/uams-web/patches-statamic-cms/patches/`. A consumer with a non-default `vendor-dir` is not covered.

**Fix:** none in the package as it stands. [Report it](https://github.com/UAMS-Web/patches-statamic-cms/issues) if a consumer needs one.
