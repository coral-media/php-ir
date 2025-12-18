<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Weighting;

use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use CoralMedia\PhpIr\Collection\VectorCollection;
use PHPUnit\Framework\TestCase;

final class TfIdfCorpusBuilderTest extends TestCase
{
    public function testBuildCorpus(): void
    {
        $documents = [
            'd1' => [0 => 2, 1 => 1],
            'd2' => [1 => 1, 2 => 3],
        ];

        $builder = new TfIdfCorpusBuilder(5);
        $collection = $builder->build($documents);

        $this->assertInstanceOf(VectorCollection::class, $collection);
        $this->assertSame(2, $collection->count());
        $this->assertSame(5, $collection->dimension());

        $this->assertArrayHasKey(0, $collection->get('d1')->toArray());
        $this->assertArrayHasKey(1, $collection->get('d1')->toArray());
        $this->assertArrayHasKey(2, $collection->get('d2')->toArray());
    }

    public function testEmptyCorpusThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new TfIdfCorpusBuilder(3))->build([]);
    }
}
