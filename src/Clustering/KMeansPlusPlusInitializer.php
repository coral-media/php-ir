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

final readonly class KMeansPlusPlusInitializer implements CentroidInitializerInterface
{
    public function __construct(
        private SimilarityInterface $similarity,
    ) {
    }

    public function initialize(
        VectorCollectionInterface $collection,
        int $k,
    ): array {
        if ($k <= 0 || $k > $collection->count()) {
            throw new InvalidArgumentException('Invalid number of clusters.');
        }

        $vectors = iterator_to_array($collection);
        $keys = array_keys($vectors);

        // 1. Choose first centroid uniformly at random
        $centroids = [];
        $firstKey = $keys[array_rand($keys)];
        $centroids[] = $vectors[$firstKey];

        // 2. Choose remaining centroids
        while (\count($centroids) < $k) {
            $distances = [];
            $sum = 0.0;

            foreach ($vectors as $key => $vector) {
                $minDistance = INF;

                foreach ($centroids as $centroid) {
                    // cosine distance
                    $distance = 1.0 - $this->similarity->similarity($vector, $centroid);
                    $minDistance = min($minDistance, $distance);
                }

                $distances[$key] = $minDistance ** 2;
                $sum += $distances[$key];
            }

            // 3. Sample proportional to distance²
            $r = lcg_value() * $sum;
            $cumulative = 0.0;

            foreach ($distances as $key => $distance) {
                $cumulative += $distance;
                if ($cumulative >= $r) {
                    $centroids[] = $vectors[$key];
                    break;
                }
            }
        }

        return $centroids;
    }
}
