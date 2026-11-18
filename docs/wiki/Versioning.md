Written against v1.1.0.

The package carries its own semantic version, and each release pins exactly one `statamic/cms` version. v1.1.0 pins `6.40.0`.

| Change | Release |
| --- | --- |
| A patch's content changes, same upstream pin | patch (`1.0.1`) |
| A patch is added or retired, or the pin moves within the upstream major | minor (`1.1.0`) |
| The pin moves to a new upstream major | major (`2.0.0`) |

## What the pin means for a consumer

- **The pin is exact.** The package requires `statamic/cms` at exactly the version its patches were written against, so a consumer on any other version cannot resolve this release. Moving `statamic/cms` means moving to the package release that pins the new version.
- **Require the package with a caret range** (`^1.0`), so a minor or patch release is picked up without editing the constraint.
- **Update it with `--with-dependencies`**: `composer update uams-web/patches-statamic-cms --with-dependencies`. Without it, `statamic/cms` stays at its locked version, and Composer stays on the newest package release that pins that version without saying so.
- **Releases are tags.** The package is read from GitHub through a `vcs` repository entry, so a release is a pushed tag and nothing is registered on Packagist.

Adopting a release takes more than the update; see [Adopting a new release](Adopting-a-new-release.md).
