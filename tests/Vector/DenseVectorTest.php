<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Vector;

use CoralMedia\PhpIr\Vector\DenseVector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DenseVectorTest extends TestCase
{
    public function testDimension(): void
    {
        $v = new DenseVector([1, 2, 3]);
        $this->assertSame(3, $v->dimension());
    }

    public function testGetReturnsValue(): void
    {
        $v = new DenseVector([1.5, 0.0, 2.5]);

        $this->assertSame(1.5, $v->get(0));
        $this->assertSame(2.5, $v->get(2));
    }

    public function testGetReturnsZeroForMissingIndex(): void
    {
        $v = new DenseVector([1, 2]);

        $this->assertSame(0.0, $v->get(10));
    }

    public function testToArray(): void
    {
        $values = [1, 2, 3];
        $v = new DenseVector($values);

        $this->assertSame(
            [1.0, 2.0, 3.0],
            $v->toArray(),
        );
    }

    public function testEmptyVectorIsNotAllowed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DenseVector([]);
    }
}
