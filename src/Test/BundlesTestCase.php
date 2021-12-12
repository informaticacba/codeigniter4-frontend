<?php

namespace Tatter\Frontend\Test;

use CodeIgniter\Publisher\Publisher;
use Tatter\Frontend\FrontendBundle;

abstract class BundlesTestCase extends TestCase
{
    private $didPublish = false;

    /**
     * Publishes all files once so they are
     * available for bundles.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Make sure everything is published
        if (! $this->didPublish) {
            foreach (Publisher::discover() as $publisher) {
                $publisher->publish();
            }

            $this->didPublish = true;
        }
    }

    /**
     * @dataProvider bundleProvider
     *
     * @param class-string<FrontendBundle> $class
     * @param string[]                     $expectedHeadFiles
     * @param string[]                     $expectedBodyFiles
     */
    public function testBundlesFiles(string $class, array $expectedHeadFiles, array $expectedBodyFiles): void
    {
        $bundle = new $class();
        $head   = $bundle->head();
        $body   = $bundle->body();

        foreach ($expectedHeadFiles as $file) {
            $this->assertStringContainsString($file, $head);
        }

        foreach ($expectedBodyFiles as $file) {
            $this->assertStringContainsString($file, $body);
        }
    }

    /**
     * Returns an array of items to test with each item
     * as a triple of [string publisherClassName, string[] headFileNames, string[] bodyFileNames]
     */
    abstract public function bundleProvider(): array;
}
