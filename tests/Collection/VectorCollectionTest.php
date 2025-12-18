<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Collection;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Vector\DenseVector;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

final class VectorCollectionTest extends TestCase
{
    public function testCountAndDimension(): void
    {
        $vectors = [
            new DenseVector([1, 2]),
            new DenseVector([3, 4]),
        ];

        $collection = new VectorCollection($vectors);

        $this->assertSame(2, $collection->count());
        $this->assertSame(2, $collection->dimension());
    }

    public function testGetVector(): void
    {
        $vectors = [
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([0, 1]),
        ];

        $collection = new VectorCollection($vectors);

        $this->assertSame($vectors['a'], $collection->get('a'));
    }

    public function testIteration(): void
    {
        $vectors = [
            new DenseVector([1, 2]),
            new DenseVector([3, 4]),
        ];

        $collection = new VectorCollection($vectors);

        $count = 0;
        foreach ($collection as $vector) {
            $this->assertInstanceOf(DenseVector::class, $vector);
            $count++;
        }

        $this->assertSame(2, $count);
    }

    public function testEmptyCollectionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new VectorCollection([]);
    }

    public function testDimensionMismatchThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new VectorCollection([
            new DenseVector([1, 2]),
            new DenseVector([1, 2, 3]),
        ]);
    }

    public function testMissingKeyThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $collection = new VectorCollection([
            new DenseVector([1]),
        ]);

        $collection->get(99);
    }
}
