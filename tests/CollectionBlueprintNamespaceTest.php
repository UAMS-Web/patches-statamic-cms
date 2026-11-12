<?php

namespace Tests\Patches;

use PHPUnit\Framework\Attributes\Test;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Collection;
use Statamic\Fields\BlueprintRepository;
use Tests\TestCase;

/**
 * Regression test for statamic-blueprint-namespace-slash.patch.
 *
 * Run it from a statamic/cms 6.40.0 checkout: it fails on stock and passes once the patches are applied.
 */
class CollectionBlueprintNamespaceTest extends TestCase
{
    private string $directory;

    public function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir().'/patches-statamic-cms-'.uniqid();
        mkdir($this->directory, 0777, true);
        file_put_contents($this->directory.'/people.yaml', "title: People\n");

        Blueprint::swap(app(BlueprintRepository::class)->setDirectories($this->directory.'/site'));

        // The way AddonServiceProvider registers an add-on's resources/blueprints directory.
        Blueprint::addNamespace('collections.people', $this->directory);
    }

    public function tearDown(): void
    {
        @unlink($this->directory.'/people.yaml');
        @rmdir($this->directory);

        parent::tearDown();
    }

    #[Test]
    public function a_collection_serves_a_blueprint_from_an_add_on_directory()
    {
        $blueprints = Collection::make('people')->entryBlueprints();

        $this->assertSame('people', $blueprints->first()->handle());
        $this->assertSame('collections.people', $blueprints->first()->namespace());
        $this->assertSame('People', $blueprints->first()->title());
    }
}
