<?php

declare(strict_types=1);

namespace CoralMedia\PhpIr\Tests\Distance;

use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CosineSimilarityTest extends TestCase
{
    public function testIdenticalVectors(): void
    {
        $v = new DenseVector([1, 2, 3]);
        $cosine = new CosineSimilarity();

        $this->assertSame(1.0, $cosine->similarity($v, $v));
    }

    public function testOrthogonalVectors(): void
    {
        $a = new DenseVector([1, 0]);
        $b = new DenseVector([0, 1]);
        $cosine = new CosineSimilarity();

        $this->assertSame(0.0, $cosine->similarity($a, $b));
    }

    public function testKnownExample(): void
    {
        $a = new DenseVector([1, 1]);
        $b = new DenseVector([1, 0]);
        $cosine = new CosineSimilarity();

        $this->assertEquals(
            0.70710678118,
            $cosine->similarity($a, $b),
            1e-10
        );
    }

    public function testZeroVector(): void
    {
        $a = new DenseVector([0, 0, 0]);
        $b = new DenseVector([1, 2, 3]);
        $cosine = new CosineSimilarity();

        $this->assertSame(0.0, $cosine->similarity($a, $b));
    }

    public function testDimensionMismatchThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $a = new DenseVector([1, 2]);
        $b = new DenseVector([1, 2, 3]);
        $cosine = new CosineSimilarity();

        $cosine->similarity($a, $b);
    }
}