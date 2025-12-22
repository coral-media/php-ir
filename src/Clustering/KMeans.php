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
use InvalidArgumentException;

final readonly class KMeans
{
    public function __construct(
        private SimilarityInterface $similarity,
        private CentroidInitializerInterface $initializer,
        private CentroidUpdaterInterface $centroidUpdater,
        private int $maxIterations = 100,
    ) {
    }

    public function cluster(
        VectorCollectionInterface $collection,
        int $k,
    ): ClusterResult {
        if ($k <= 0 || $k > $collection->count()) {
            throw new InvalidArgumentException('Invalid number of clusters.');
        }

        // 1. Initialize centroids
        $centroids = $this->initializer->initialize($collection, $k);

        $assignments = [];

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

            // Convergence check
            if ($newAssignments === $assignments) {
                break;
            }

            $assignments = $newAssignments;

            // 3. Update centroids (delegated)
            foreach ($assignments as $i => $clusterKeys) {
                if ([] === $clusterKeys) {
                    continue;
                }

                $centroids[$i] = $this->centroidUpdater->update(
                    $collection,
                    $clusterKeys,
                );
            }
        }

        return new ClusterResult($assignments, $centroids);
    }
}
