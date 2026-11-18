`uams-web/patches-statamic-cms` is the one package of patches that UAMS-Web repositories apply to [`statamic/cms`](https://github.com/statamic/cms), through [`cweagans/composer-patches`](https://github.com/cweagans/composer-patches) 2.x. A patch is changed once here and retires from every consumer in one release.

This wiki holds the reference and the guides: what each patch changes, how the package is versioned, how a consumer adopts a new release, what goes wrong and why, and how a patch retires. The [README](https://github.com/UAMS-Web/patches-statamic-cms#readme) keeps the overview, the installation, the list of patches in the order they apply, and how to verify the set.

## Getting started

- [Installation (README)](https://github.com/UAMS-Web/patches-statamic-cms#install): add the package to an application or a package.

## Guides

- [Adopting a new release](Adopting-a-new-release.md): move a consumer to a new release of this package.

## Troubleshooting

- [Troubleshooting](Troubleshooting.md): one entry per symptom, with its cause and fix.

## Reference

- [Patches](Patches.md): what each patch changes in `statamic/cms`, and what it waits on to retire.
- [Versioning](Versioning.md): which change makes a patch, minor or major release.

## Project

- [Project](Project.md): where the package is developed, how a patch retires, and how to report a problem.
