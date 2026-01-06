<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Distance;

use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\SparseVector;
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

    public function testSphericalCosineUsesDotProduct(): void
    {
        // Unit-length vectors
        $a = new DenseVector([1.0, 0.0]);
        $b = new DenseVector([0.6, 0.8]); // already normalized

        $cosine = new CosineSimilarity(normalize: false);

        // Dot product = 1*0.6 + 0*0.8 = 0.6
        $this->assertEqualsWithDelta(
            0.6,
            $cosine->similarity($a, $b),
            1e-10,
        );
    }

    public function testOrthogonalVectors(): void
    {
        $a = new DenseVector([1, 0]);
        $b = new DenseVector([0, 1]);
        $cosine = new CosineSimilarity();

        $this->assertSame(0.0, $cosine->similarity($a, $b));
    }

    public function testSparseVectors(): void
    {
        $a = new SparseVector([1 => 1.0, 3 => 2.0], 5);
        $b = new SparseVector([1 => 2.0, 4 => 1.0], 5);

        $cosine = new CosineSimilarity();

        $this->assertEqualsWithDelta(
            0.4,
            $cosine->similarity($a, $b),
            1e-10,
        );
    }

    public function testKnownExample(): void
    {
        $a = new DenseVector([1, 1]);
        $b = new DenseVector([1, 0]);
        $cosine = new CosineSimilarity();

        $this->assertEqualsWithDelta(
            0.70710678118,
            $cosine->similarity($a, $b),
            1e-10,
        );
    }

    public function testZeroVector(): void
    {
        $a = new DenseVector([0, 0, 0]);
        $b = new DenseVector([1, 2, 3]);
        $cosine = new CosineSimilarity();

        $this->assertSame(0.0, $cosine->similarity($a, $b));
    }
}
