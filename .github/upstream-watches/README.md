<!--
  Synced from UAMS-Web/uams-claude-skills: shared/.claude/skills/patch-package/templates/upstream-watches-README.md
  Edit it there, not here; the next sync overwrites this copy.
  Delivered to this repository through the profile(s): patches.
-->
# Upstream watches

<!-- cspell:ignore Packagist -->

One file per upstream issue or pull request this repository is waiting on. The `watch-upstream` workflow (`.github/workflows/watch-upstream.yml`) reads every `*.json` file here once a day and comments on the row's tracking issue when something moves. This README is also what keeps the directory tracked while it holds no rows, which is a valid state: the workflow then has nothing to do.

## Row format

Name the file `<tracker>-<owner>-<repo>-<number>.json`, for example `412-laravel-framework-58123.json` for tracker `412` watching `laravel/framework#58123` (`/` in the repository becomes `-`). The loader rejects a file whose name does not match its contents, so the name is always unique and says what it watches.

```json
{ "tracker": 412, "repo": "laravel/framework", "kind": "pr", "number": 58123, "composer": "laravel/framework", "released": true }
```

| Key | Required | Meaning |
| --- | --- | --- |
| `tracker` | yes | Issue number in this repository that receives the comments. Closing it turns the watch off. |
| `repo` | yes | The upstream repository, `owner/name`. |
| `kind` | yes | `issue` or `pr`. |
| `number` | yes | The upstream issue or pull request number. |
| `composer` | no | A `vendor/package` name. Adds a signal for a Packagist release newer than the version in `composer.lock`. |
| `released` | no | `true` on a `pr` row only. Adds a signal for the first release on the pull request's base-branch line that contains the merge commit. |

Unknown keys, a missing key and a wrong type fail the run with the file named, so a malformed row is never silently skipped.

## Adding and retiring a row

- **Add** a row in the same pull request that adds the local patch it exists for, so the patch and its removal trigger land together.
- **Retire** a row in the pull request that drops the patch: delete the file and close the tracking issue. Closing the tracker alone stops the comments; deleting the file stops the workflow reading it.
- Two rows under one tracker share an off switch, so give threads separate trackers whenever either can outlive the other.

## Notes on current rows

Add one line per row here saying what the upstream thread is and what to do when it moves.

- `8-statamic-cms-15881.json`: the pull request that makes `makeBlueprintFromFile()` normalize the namespace (the same change as `statamic-blueprint-namespace-slash.patch`). When it merges and a release on 6.x carries it, retire that patch through #8. It has no `composer` key because this package carries no `composer.lock`; `released: true` reads the tags instead.
- `8-statamic-cms-15880.json`: the issue that report is filed under. It closes with the pull request above, so it adds a signal only if maintainers close it without merging; both rows share tracker #8, which is safe because they are the same contribution.

Threads with no row, and why: #2 names `statamic/cms#15550` and `#15551`, which fix a different defect (the shared `Traverser::filter()`), so watching them says nothing about the vanished-file patch; #2 waits on a ruling about where to report that defect. #3, #4 and #5 name no upstream thread, because none has been filed.
