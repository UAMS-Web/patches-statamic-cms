Written against v1.1.0.

The package carries 11 patches, all against `statamic/cms` 6.40.0. This page groups them by what they change and says what each group waits on before it can retire. The order they apply in, which matters, is the [README's list](https://github.com/UAMS-Web/patches-statamic-cms#patches), the same order as `extra.patches` in [`composer.json`](https://github.com/UAMS-Web/patches-statamic-cms/blob/main/composer.json). The description keys there have gained upstream references on `main` since v1.1.0; a consumer copying them takes them from the `composer.json` of the release it adopts.

- [Create labels that follow the blueprint](#create-labels-that-follow-the-blueprint)
- [A Stache file removed while it is being read](#a-stache-file-removed-while-it-is-being-read)
- [Collection blueprints served from an add-on directory](#collection-blueprints-served-from-an-add-on-directory)
- [A Stache index built from a stale collection](#a-stache-index-built-from-a-stale-collection)

## Create labels that follow the blueprint

| Patch | Changes |
| --- | --- |
| `statamic-collection-create-label-blueprint.patch` | `Collection::createLabel()` in `src/Entries/Collection.php` |
| `statamic-taxonomy-create-label-blueprint.patch` | `Taxonomy::createLabel()` in `src/Taxonomies/Taxonomy.php` |

Stock 6.40.0 reads the label on a collection's create button from the translation key `messages.<handle>_collection_create_entry`, and a taxonomy's from `messages.<handle>_taxonomy_create_term`. The patches move both keys into the `statamic::` namespace, so the translation is read from `lang/vendor/statamic/<locale>/messages.php` rather than the application's own `lang/<locale>/messages.php`. When the request carries a `blueprint` query parameter, the key with `.<blueprint>` appended is tried first, so each blueprint can have its own label. Where no translation exists, or the translation is not a string, the label falls back to `Create Entry` or `Create Term`.

Retires through [#4](https://github.com/UAMS-Web/patches-statamic-cms/issues/4). No upstream report has been filed yet.

## A Stache file removed while it is being read

| Patch | Changes |
| --- | --- |
| `statamic-stache-traverser-vanished-file.patch` | `Traverser` in `src/Stache/Traverser.php` |
| `statamic-stache-store-vanished-file-paths.patch` | `Store::paths()` in `src/Stache/Stores/Store.php` |
| `statamic-stache-store-vanished-file-modified.patch` | `Store::getItemFromModifiedPath()` in `src/Stache/Stores/Store.php` |
| `statamic-stache-terms-vanished-file-modified.patch` | `TaxonomyTermsStore::getItemFromModifiedPath()` |
| `statamic-stache-terms-vanished-file-paths.patch` | `TaxonomyTermsStore::paths()` |
| `statamic-stache-terms-vanished-file-get-item.patch` | `TaxonomyTermsStore::getItem()` |
| `statamic-stache-basic-vanished-file-get-item.patch` | `BasicStore::getItem()` in `src/Stache/Stores/BasicStore.php` |

When a content file is deleted while the Stache is listing or reading it, stock 6.40.0 either aborts the traversal or rebuilds the missing file as a new, empty item. With the patches, a file that a fresh directory listing shows is gone is skipped instead: the traverser leaves it out, the stores return nothing for it, and the terms store returns no items where its parent skipped the file. A file that is still listed, or whose directory cannot be read, is handled as stock 6.40.0 handles it.

The first patch was written for [`UAMS-Web/uams-statamic-archive#2241`](https://github.com/UAMS-Web/uams-statamic-archive/issues/2241) and the other six for [`UAMS-Web/uams-statamic-archive#2643`](https://github.com/UAMS-Web/uams-statamic-archive/issues/2643). Upstream: the report [`statamic/cms#15902`](https://github.com/statamic/cms/issues/15902) and the pull request [`statamic/cms#15903`](https://github.com/statamic/cms/pull/15903). The traverser patch retires through [#2](https://github.com/UAMS-Web/patches-statamic-cms/issues/2) and the six store patches through [#3](https://github.com/UAMS-Web/patches-statamic-cms/issues/3).

## Collection blueprints served from an add-on directory

| Patch | Changes |
| --- | --- |
| `statamic-blueprint-namespace-slash.patch` | `BlueprintRepository::makeBlueprintFromFile()` in `src/Fields/BlueprintRepository.php` |

A collection blueprint that an add-on serves from its own directory arrives with a namespace written with slashes, which stock 6.40.0 does not match against its registered namespaces, and the request fails with `Undefined array key 0`. The patch rewrites the slashes as dots before the lookup.

Written for [`UAMS-Web/uams-statamic#281`](https://github.com/UAMS-Web/uams-statamic/issues/281), and retires through [#8](https://github.com/UAMS-Web/patches-statamic-cms/issues/8). Upstream: the report [`statamic/cms#15880`](https://github.com/statamic/cms/issues/15880) and the pull request [`statamic/cms#15881`](https://github.com/statamic/cms/pull/15881). The regression test is [`tests/CollectionBlueprintNamespaceTest.php`](https://github.com/UAMS-Web/patches-statamic-cms/blob/main/tests/CollectionBlueprintNamespaceTest.php); the README's [Verifying the set](https://github.com/UAMS-Web/patches-statamic-cms#verifying-the-set) says how to run it.

## A Stache index built from a stale collection

| Patch | Changes |
| --- | --- |
| `statamic-stache-clear-file-items.patch` | `Store::clear()` in `src/Stache/Stores/Store.php` |

A query outside a warm leaves the collection it read in the store, and nothing dropped it, so the next `warm()` built every index from that collection. Since `statamic/cms` 6.43.0 (`statamic/cms#15897`) that includes the `title` index that term queries read, so a term file written after a query that found nothing was not returned after `clear()` and `warm()`, after `Stache::refresh()`, or after a `warm()` in a queue worker or Octane process, whose between-jobs and between-requests reset clears each store's paths and indexes but not this collection. The patch sets `$this->fileItems = null;` at the start of `warm()`, the only place the collection is read back, so it covers every one of those paths. `clear()` is left alone: it needs no reset of its own.

Written for [`UAMS-Web/aether-wordpress-importer#618`](https://github.com/UAMS-Web/aether-wordpress-importer/issues/618), and retires through [#33](https://github.com/UAMS-Web/patches-statamic-cms/issues/33) once a `statamic/cms` release carries an equivalent fix. No upstream report has been filed yet.

## When a patch retires

A patch retires once a `statamic/cms` release carries an equivalent fix. The package then releases without the patch and with its pin moved to that `statamic/cms` version, in one release; see [Project](Project.md#how-a-patch-retires).
