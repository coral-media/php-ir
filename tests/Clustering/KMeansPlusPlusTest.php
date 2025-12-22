<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Clustering;

use CoralMedia\PhpIr\Clustering\KMeans;
use CoralMedia\PhpIr\Clustering\KMeansPlusPlusInitializer;
use CoralMedia\PhpIr\Clustering\MeanCentroidUpdater;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class KMeansPlusPlusTest extends TestCase
{
    public function testClustersSimpleDataWithKMeansPlusPlus(): void
    {
        // Ensure deterministic centroid selection
        mt_srand(42);

        $collection = new VectorCollection([
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([0.9, 0.1]),
            'c' => new DenseVector([0, 1]),
            'd' => new DenseVector([0.1, 0.9]),
        ]);

        $similarity = new CosineSimilarity();
        $initializer = new KMeansPlusPlusInitializer($similarity);

        $kMeans = new KMeans($similarity, $initializer, new MeanCentroidUpdater(), 20);
        $result = $kMeans->cluster($collection, 2);

        $this->assertCount(2, $result->assignments);

        $clusterSizes = array_map('count', $result->assignments);
        sort($clusterSizes);

        $this->assertSame([2, 2], $clusterSizes);
    }
}
