<?php

namespace Tests\Patches;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Stache;
use Statamic\Facades\Taxonomy;
use Tests\PreventSavingStacheItemsToDisk;
use Tests\TestCase;

/**
 * Regression test for statamic-stache-clear-file-items.patch.
 *
 * Run it from a statamic/cms 6.43.1 checkout: it fails on stock and passes once the patches are applied.
 */
class StacheClearFileItemsTest extends TestCase
{
    use PreventSavingStacheItemsToDisk;

    private string $directory;

    public function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir().'/patches-statamic-cms-'.uniqid();
        mkdir($this->directory.'/tags', 0777, true);

        Stache::store('terms')->directory($this->directory);
        Taxonomy::make('tags')->save();
    }

    public function tearDown(): void
    {
        @unlink($this->directory.'/tags/default_news.yaml');
        @rmdir($this->directory.'/tags');
        @rmdir($this->directory);

        parent::tearDown();
    }

    #[Test]
    public function a_term_written_after_an_empty_query_is_indexed_after_clear_and_warm()
    {
        $store = Stache::store('terms')->store('tags');

        // A query outside a warm reads the empty directory and leaves that result in the store.
        $this->assertCount(0, $store->index('title')->keys());

        file_put_contents($this->directory.'/tags/default_news.yaml', "title: Default News\n");

        $store->clear();
        $store->warm();

        $this->assertCount(1, $store->index('title')->keys());
    }

    #[Test]
    public function a_term_written_after_an_empty_query_is_indexed_after_a_stache_refresh()
    {
        $store = Stache::store('terms')->store('tags');

        $this->assertCount(0, $store->index('title')->keys());

        file_put_contents($this->directory.'/tags/default_news.yaml', "title: Default News\n");

        Stache::refresh();

        $this->assertCount(1, Stache::store('terms')->store('tags')->index('title')->keys());
    }
}
