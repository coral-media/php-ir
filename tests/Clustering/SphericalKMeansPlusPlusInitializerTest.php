<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Clustering;

use CoralMedia\PhpIr\Clustering\Initializer\SphericalKMeansPlusPlusInitializer;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class SphericalKMeansPlusPlusInitializerTest extends TestCase
{
    public function testInitialCentroidsAreDistinctAndUnitLength(): void
    {
        mt_srand(42);

        $collection = new VectorCollection([
            'a' => new DenseVector([1.0, 0.0]),
            'b' => new DenseVector([0.9, 0.1]),
            'c' => new DenseVector([0.0, 1.0]),
            'd' => new DenseVector([0.1, 0.9]),
        ]);

        $initializer = new SphericalKMeansPlusPlusInitializer(
            new CosineSimilarity(),
        );

        $centroids = $initializer->initialize($collection, 2);

        // Invariant 1: correct count
        $this->assertCount(2, $centroids);

        // Invariant 2: centroids are distinct objects
        $this->assertNotSame($centroids[0], $centroids[1]);

        // Invariant 3: centroids are unit length
        foreach ($centroids as $centroid) {
            $norm = sqrt(array_sum(
                array_map(
                    static fn (float $v): float => $v * $v,
                    $centroid->toArray(),
                ),
            ));

            $this->assertEqualsWithDelta(
                1.0,
                $norm,
                1e-6,
                'Centroid must lie on unit hypersphere',
            );
        }
    }
}
