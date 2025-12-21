<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Distance;

use CoralMedia\PhpIr\Distance\EuclideanSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class EuclideanSimilarityTest extends TestCase
{
    public function testIdenticalVectorsHaveMaxSimilarity(): void
    {
        $sim = new EuclideanSimilarity();

        $a = new DenseVector([1.0, 2.0, 3.0]);
        $b = new DenseVector([1.0, 2.0, 3.0]);

        $this->assertSame(1.0, $sim->similarity($a, $b));
    }

    public function testMoreDistantVectorsHaveLowerSimilarity(): void
    {
        $sim = new EuclideanSimilarity();

        $a = new DenseVector([0.0, 0.0]);
        $b = new DenseVector([1.0, 0.0]);
        $c = new DenseVector([3.0, 0.0]);

        $this->assertGreaterThan(
            $sim->similarity($a, $c),
            $sim->similarity($a, $b),
        );
    }
}
