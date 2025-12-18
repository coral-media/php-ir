<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Vector;

use CoralMedia\PhpIr\Vector\SparseVector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SparseVectorTest extends TestCase
{
    public function testDimension(): void
    {
        $v = new SparseVector([1 => 2.5, 3 => 1.0], 5);
        $this->assertSame(5, $v->dimension());
    }

    public function testGetReturnsValue(): void
    {
        $v = new SparseVector([2 => 3.5], 4);

        $this->assertSame(3.5, $v->get(2));
    }

    public function testGetReturnsZeroForMissingIndex(): void
    {
        $v = new SparseVector([1 => 1.0], 3);

        $this->assertSame(0.0, $v->get(0));
        $this->assertSame(0.0, $v->get(2));
    }

    public function testGetReturnsZeroForOutOfBounds(): void
    {
        $v = new SparseVector([], 3);

        $this->assertSame(0.0, $v->get(-1));
        $this->assertSame(0.0, $v->get(10));
    }

    public function testToArrayReturnsOnlyNonZeroValues(): void
    {
        $values = [0 => 1.0, 4 => 2.0];
        $v = new SparseVector($values, 5);

        $this->assertSame($values, $v->toArray());
    }

    public function testInvalidDimensionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SparseVector([], 0);
    }

    public function testIndexExceedingDimensionThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new SparseVector([5 => 1.0], 5);
    }
}
