# uams-web/patches-statamic-cms

Patches that UAMS-Web repositories apply to [`statamic/cms`](https://github.com/statamic/cms) with [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches) 2.x. One package replaces the copies each repository carried, so a patch is changed once and retires everywhere in one release.

This package pins `statamic/cms` to exactly `6.40.0`. Every patch is written against that release.

This repository is private. `statamic/cms` is under Statamic LLC's license, which does not allow its code to be reused in other projects without consent, so these patches are not published.

## Install

An application requires it in `require` (the plugin applies a dependency's patches only from `packages` in `composer.lock`):

```bash
composer require --no-plugins uams-web/patches-statamic-cms:^1.0
```

A package that another repository installs requires it with `--dev` and lists the patch files in its own root `patches.json`. The full procedure, including `composer patches-relock` and `composer patches-repatch`, is the `upstream-patch-lifecycle` skill's *Moving a consumer to the package*.

Patch paths are relative to the consumer's root and start `vendor/uams-web/patches-statamic-cms/patches/`, so a consumer with a non-default `vendor-dir` is not covered.

A consumer needs Composer authentication to this private repository (a GitHub token with read access to it) and a `vcs` entry for it in `repositories`.

## Patches

Applied in this order, which is the order in `composer.json`. A later patch is cut against the output of the earlier ones, so none is reordered without re-cutting those after it.

- **`statamic-collection-create-label-blueprint.patch`**: Add blueprint-aware collection create labels
- **`statamic-taxonomy-create-label-blueprint.patch`**: Add blueprint-aware taxonomy create labels
- **`statamic-updates-overview-badge.patch`**: Align CP updates badge with updater availableUpdatesCount
- **`statamic-stache-traverser-vanished-file.patch`**: Skip a Stache file removed between listing and stat so a concurrent delete cannot abort the traversal (temporary; see UAMS-Web/uams-statamic-archive#2241)
- **`statamic-stache-store-vanished-file-paths.patch`**: Skip a Stache file removed after its stat in Store::paths() so it is not rebuilt as a new, empty item (temporary; see UAMS-Web/uams-statamic-archive#2643)
- **`statamic-stache-store-vanished-file-modified.patch`**: Skip a Stache file removed after the change scan in Store::getItemFromModifiedPath() so it is not rebuilt as a new, empty item (temporary; see UAMS-Web/uams-statamic-archive#2643)
- **`statamic-stache-terms-vanished-file-modified.patch`**: Return no items from TaxonomyTermsStore::getItemFromModifiedPath() when the parent skips a vanished file, rather than fatal on null (temporary; see UAMS-Web/uams-statamic-archive#2643)
- **`statamic-stache-terms-vanished-file-paths.patch`**: Skip a Stache term file removed after its stat in TaxonomyTermsStore::paths() so it is not rebuilt as a new, empty term (temporary; see UAMS-Web/uams-statamic-archive#2643)
- **`statamic-stache-terms-vanished-file-get-item.patch`**: Return null from TaxonomyTermsStore::getItem() for a term file removed after its path was recorded, rather than rebuild it (temporary; see UAMS-Web/uams-statamic-archive#2643)
- **`statamic-stache-basic-vanished-file-get-item.patch`**: Return null from BasicStore::getItem() for a file removed after its path was recorded, rather than rebuild it (temporary; see UAMS-Web/uams-statamic-archive#2643)

Each description names the ticket that tracks the upstream fix, where there is one.

## Versioning

| Change | Release |
| --- | --- |
| A patch's content changes, same upstream pin | patch |
| A patch is added or retired, or the pin moves within the upstream major | minor |
| The pin moves to a new upstream major | major |

## Verifying the set

Apply the 10 patches in order to the `v6.40.0` tag of `statamic/cms` with `git apply -p1`. Applying the first patch a second time must fail.

## License

Proprietary, not for redistribution. The patches modify `statamic/cms`, which is licensed by Statamic LLC.
