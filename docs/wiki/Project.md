The package is developed in [`UAMS-Web/patches-statamic-cms`](https://github.com/UAMS-Web/patches-statamic-cms). It exists so the UAMS-Web repositories that patch `statamic/cms` share one copy of each patch instead of carrying their own.

## How a patch retires

Each patch follows a four-ticket chain: write the patch, report the bug upstream, offer the fix upstream as a pull request, and retire the patch once a `statamic/cms` release carries the fix. The last ticket stays blocked until the fix is both merged and tagged: a merge on a branch the package cannot install retires nothing.

A scheduled workflow, [`watch-upstream.yml`](https://github.com/UAMS-Web/patches-statamic-cms/blob/main/.github/workflows/watch-upstream.yml), watches the upstream threads listed in [`.github/upstream-watches/`](https://github.com/UAMS-Web/patches-statamic-cms/tree/main/.github/upstream-watches) and comments on the tracking issue here when one is closed, merged or released.

A patch retires from the package, not from each consumer. When `statamic/cms` ships the fix, the package releases without that patch and with its pin moved to that version, in one release; consumers then [adopt the release](Adopting-a-new-release.md). Once the last patch retires, the repository is archived.

## Reporting a problem

[Open an issue](https://github.com/UAMS-Web/patches-statamic-cms/issues) here for a problem with a patch, the package or these pages. A bug in `statamic/cms` itself that no patch here touches belongs in the [`statamic/cms` tracker](https://github.com/statamic/cms/issues).

## Releases

[Releases](https://github.com/UAMS-Web/patches-statamic-cms/releases) are tags; [Versioning](Versioning.md) says what each kind means. This wiki is published from [`docs/wiki/`](https://github.com/UAMS-Web/patches-statamic-cms/tree/main/docs/wiki) on each published release and by hand, and each publish replaces the whole wiki.

## License

The added lines in the patches are UAMS-Web's. Their removed and context lines are `statamic/cms` code, licensed by Statamic LLC; the [README](https://github.com/UAMS-Web/patches-statamic-cms#license) states the terms.
