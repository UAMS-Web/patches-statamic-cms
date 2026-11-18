Written against v1.1.0, and `cweagans/composer-patches` 2.0.0.

A consumer already requires this package and a new release is out: a patch changed, was added or retired, or the `statamic/cms` pin moved. This is not an ordinary dependency update, because the plugin reads the committed `patches.lock.json` and never re-resolves it. A machine with the old patch cached would apply the old text, and one without it fails on a hash mismatch.

The procedure is *`patches.lock.json` in a consumer* in the `upstream-patch-lifecycle` skill that each consumer repository carries; the end-to-end adoption is not yet measured there.

## Steps

1. **In a package that lists the patches in its root `patches.json`**, edit `patches.json` to match the release first: the same patch files, in the release's order, with the description keys from that release's `composer.json`. An application skips this step; it applies the package's own list.
2. **Move the package and `statamic/cms` together**, with plugins off:

   ```bash
   composer update uams-web/patches-statamic-cms --with-dependencies --no-plugins
   ```

3. **Relock and reapply the patches:**

   ```bash
   composer patches-relock
   composer patches-repatch
   ```

4. **Prove the set applies cold.** Move `vendor/` aside and run `composer install -v`. Each patch's description appears in the output; without `-v` the install prints only `Patching statamic/cms`.
5. **Commit `composer.json`, `composer.lock`, `patches.json` where it changed, and `patches.lock.json` together.**

**Note:** a Dependabot pull request that moves this package needs these steps on its branch before it merges.

## When the release retires the last patch

The consumer removes the package in one change, and requires `statamic/cms` directly only if it did before adopting the package. Where no other patch remains, it also removes `patches.json`, `patches.lock.json` and `cweagans/composer-patches`, in the order the `upstream-patch-lifecycle` skill's *Removing a patch* gives.

## Reference

- [Versioning](Versioning.md): what each kind of release means, and why the update needs `--with-dependencies`.
- [Patches](Patches.md): what each patch in the release changes.
- [Troubleshooting](Troubleshooting.md): what the steps above prevent.
