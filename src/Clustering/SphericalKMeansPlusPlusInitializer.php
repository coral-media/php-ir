<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final class SphericalKMeansPlusPlusInitializer implements CentroidInitializerInterface
{
    public function __construct(
        private SimilarityInterface $similarity,
    ) {
    }

    /**
     * @return list<VectorInterface>
     */
    public function initialize(
        VectorCollectionInterface $collection,
        int $k,
    ): array {
        if ($k <= 0 || $k > $collection->count()) {
            throw new InvalidArgumentException('Invalid k.');
        }

        $vectors = iterator_to_array($collection);

        // 1. Pick first centroid uniformly at random
        $centroids = [];
        $centroids[] = $vectors[array_rand($vectors)];

        // 2. Pick remaining centroids
        while (\count($centroids) < $k) {
            $distances = [];
            $total = 0.0;

            foreach ($vectors as $key => $vector) {
                $minDistance = INF;

                foreach ($centroids as $centroid) {
                    $similarity = $this->similarity
                        ->similarity($vector, $centroid)
                    ;

                    // Angular distance
                    $distance = 1.0 - $similarity;
                    $minDistance = min($minDistance, $distance);
                }

                $distances[$key] = $minDistance;
                $total += $minDistance;
            }

            // Numerical safety fallback
            if (0.0 === $total) {
                break;
            }

            // 3. Sample proportional to angular distance
            $threshold = lcg_value() * $total;
            $running = 0.0;

            foreach ($distances as $key => $distance) {
                $running += $distance;
                if ($running >= $threshold) {
                    $centroids[] = $vectors[$key];
                    break;
                }
            }
        }

        return $centroids;
    }
}
