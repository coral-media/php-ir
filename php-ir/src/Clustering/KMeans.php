<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Vector\DenseVector;
use InvalidArgumentException;

final readonly class KMeans
{
    public function __construct(
        private readonly SimilarityInterface $similarity,
        private readonly CentroidInitializerInterface $initializer = new RandomCentroidInitializer(),
        private readonly int $maxIterations = 100,
    ) {
    }

    public function cluster(
        VectorCollectionInterface $collection,
        int $k,
    ): ClusterResult {
        if ($k <= 0 || $k > $collection->count()) {
            throw new InvalidArgumentException('Invalid number of clusters.');
        }

        $keys = array_keys(iterator_to_array($collection));
        shuffle($keys);

        // 1. Initialize centroids
        $centroids = $this->initializer->initialize($collection, $k);

        $assignments = [];
        $normalizer = new L2Normalizer();

        for ($iteration = 0; $iteration < $this->maxIterations; $iteration++) {
            $newAssignments = array_fill(0, $k, []);

            // 2. Assignment step
            foreach ($collection as $key => $vector) {
                $bestCluster = null;
                $bestScore = -INF;

                foreach ($centroids as $i => $centroid) {
                    $score = $this->similarity->similarity($vector, $centroid);

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestCluster = $i;
                    }
                }

                $newAssignments[$bestCluster][] = $key;
            }

            if ($newAssignments === $assignments) {
                break; // converged
            }

            $assignments = $newAssignments;

            // 3. Update centroids
            foreach ($assignments as $i => $clusterKeys) {
                if ([] === $clusterKeys) {
                    continue;
                }

                $sum = array_fill(0, $collection->dimension(), 0.0);

                foreach ($clusterKeys as $key) {
                    $vector = $collection->get($key);
                    foreach ($vector->toArray() as $index => $value) {
                        $sum[$index] += $value;
                    }
                }

                $mean = array_map(
                    static fn (float $v): float => $v / \count($clusterKeys),
                    $sum,
                );

                $centroids[$i] = $normalizer->normalize(
                    new DenseVector($mean),
                );
            }
        }

        return new ClusterResult($assignments, $centroids);
    }
}
