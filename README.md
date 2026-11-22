# uams-web/patches-statamic-cms

Patches that UAMS-Web repositories apply to [`statamic/cms`](https://github.com/statamic/cms) with [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches) 2.x. One package replaces the copies each repository carried, so a patch is changed once and retires everywhere in one release.

This package pins `statamic/cms` to exactly `6.43.1`. Every patch is written against that release.

The [wiki](https://github.com/UAMS-Web/patches-statamic-cms/wiki) holds the reference and the guides: what each patch changes, adopting a new release, troubleshooting, and how a patch retires.

## Install

This package is read from GitHub with a `vcs` repository, not from Packagist. The consumer's root `composer.json` lists it (Composer reads `repositories` only from the root project):

```json
"repositories": [
  { "type": "vcs", "url": "https://github.com/UAMS-Web/patches-statamic-cms.git" }
]
```

An application requires it in `require` (the plugin applies a dependency's patches only from `packages` in `composer.lock`):

```bash
composer require --no-plugins uams-web/patches-statamic-cms:^1.0
```

A package that another repository installs requires it with `--dev` and lists the patch files in its own root `patches.json`. The full procedure, including `composer patches-relock` and `composer patches-repatch`, is the `upstream-patch-lifecycle` skill's *Moving a consumer to the package*.

Patch paths are relative to the consumer's root and start `vendor/uams-web/patches-statamic-cms/patches/`, so a consumer with a non-default `vendor-dir` is not covered.

## Patches

Applied in this order, which is the order in `composer.json`. A later patch is cut against the output of the earlier ones, so none is reordered without re-cutting those after it.

- **`statamic-collection-create-label-blueprint.patch`**: Add blueprint-aware collection create labels
- **`statamic-taxonomy-create-label-blueprint.patch`**: Add blueprint-aware taxonomy create labels
- **`statamic-updates-overview-badge.patch`**: Align CP updates badge with updater availableUpdatesCount
- **`statamic-blueprint-namespace-slash.patch`**: Resolve a collection blueprint served from an add-on directory by normalizing the namespace in makeBlueprintFromFile() (temporary; see UAMS-Web/uams-statamic#281; upstream: statamic/cms#15880, statamic/cms#15881)

Each description names the ticket that tracks the upstream fix, where there is one.

What each patch changes, grouped by area, is on the wiki's [Patches](https://github.com/UAMS-Web/patches-statamic-cms/wiki/Patches) page.

## Versioning

Each release pins exactly one `statamic/cms` version, and the kind of change decides whether it is a patch, minor or major release. The table, and what the exact pin means for a consumer, are on the wiki's [Versioning](https://github.com/UAMS-Web/patches-statamic-cms/wiki/Versioning) page.

## Verifying the set

Apply the 4 patches in order to the `v6.43.1` tag of `statamic/cms` with `git apply -p1`. Applying the first patch a second time must fail.

`tests/CollectionBlueprintNamespaceTest.php` is the regression test for `statamic-blueprint-namespace-slash.patch`. Copy it into the `tests/` directory of that checkout and run it with `vendor/bin/phpunit`: it fails on stock `statamic/cms` 6.40.0 (`Undefined array key 0`) and passes once the patches are applied.

## License

The added lines in the patches are UAMS-Web's. Their removed and context lines are `statamic/cms` code, which is licensed by Statamic LLC under terms this repository does not change, and no license is granted here for that code.
