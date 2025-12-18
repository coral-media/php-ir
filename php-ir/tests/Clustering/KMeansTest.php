<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Clustering;

use CoralMedia\PhpIr\Clustering\RandomCentroidInitializer;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Clustering\KMeans;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class KMeansTest extends TestCase
{
    public function testClustersSimpleData(): void
    {
        $collection = new VectorCollection([
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([0.9, 0.1]),
            'c' => new DenseVector([0, 1]),
            'd' => new DenseVector([0.1, 0.9]),
        ]);

        $cosineSimilarity = new CosineSimilarity();
        $initializer = new RandomCentroidInitializer();

        $kMeans = new KMeans($cosineSimilarity, $initializer, 20);
        $result = $kMeans->cluster($collection, 2);

        $this->assertCount(2, $result->assignments);

        $clusterSizes = array_map('count', $result->assignments);
        sort($clusterSizes);

        $this->assertSame([2, 2], $clusterSizes);
    }
}
