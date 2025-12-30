<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering\Quality;

use CoralMedia\PhpIr\Clustering\ClusterResult;
use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final readonly class ClusterQualityCalculator
{
    public function __construct(
        private SimilarityInterface $similarity,
    ) {
    }

    public function evaluate(
        VectorCollectionInterface $collection,
        ClusterResult $result,
    ): ClusterQualityReport {
        if ($collection->count() === 0) {
            throw new InvalidArgumentException('Cannot evaluate empty collection.');
        }

        if ([] === $result->assignments) {
            throw new InvalidArgumentException('ClusterResult has no assignments.');
        }

        $cohesionPerCluster = $this->computeCohesion(
            $collection,
            $result,
        );

        $averageCohesion = $this->average($cohesionPerCluster);

        $interCentroidSimilarity = $this->computeInterCentroidSimilarity(
            $result->centroids,
        );

        $averageInterCentroidSimilarity = $this->average(
            $interCentroidSimilarity,
        );

        [$clusterEntropy, $normalizedClusterEntropy] =
            $this->computeClusterEntropy(
                $result,
                $collection->count(),
            );

        $qualityScore = $averageInterCentroidSimilarity > 0.0
            ? $averageCohesion / $averageInterCentroidSimilarity
            : $averageCohesion;

        return new ClusterQualityReport(
            cohesionPerCluster: $cohesionPerCluster,
            averageCohesion: $averageCohesion,
            interCentroidSimilarity: $interCentroidSimilarity,
            averageInterCentroidSimilarity: $averageInterCentroidSimilarity,
            clusterEntropy: $clusterEntropy,
            normalizedClusterEntropy: $normalizedClusterEntropy,
            qualityScore: $qualityScore,
        );
    }

    /**
     * @return array<int, float>
     */
    private function computeCohesion(
        VectorCollectionInterface $collection,
        ClusterResult $result,
    ): array {
        $cohesion = [];

        foreach ($result->assignments as $clusterIndex => $keys) {
            if ([] === $keys) {
                $cohesion[$clusterIndex] = 0.0;
                continue;
            }

            $centroid = $result->centroids[$clusterIndex];
            $cohesion[$clusterIndex] = $this->clusterCohesion(
                $collection,
                $centroid,
                $keys,
            );
        }

        return $cohesion;
    }

    /**
     * @param list<int|string> $keys
     */
    private function clusterCohesion(
        VectorCollectionInterface $collection,
        VectorInterface $centroid,
        array $keys,
    ): float {
        $sum = 0.0;

        foreach ($keys as $key) {
            $sum += $this->similarity->similarity(
                $collection->get($key),
                $centroid,
            );
        }

        return $sum / \count($keys);
    }

    /**
     * @param array<int, VectorInterface> $centroids
     * @return array<string, float>
     */
    private function computeInterCentroidSimilarity(
        array $centroids,
    ): array {
        $similarity = [];
        $k = \count($centroids);

        for ($i = 0; $i < $k; $i++) {
            for ($j = $i + 1; $j < $k; $j++) {
                $similarity["{$i}-{$j}"] = $this->similarity->similarity(
                    $centroids[$i],
                    $centroids[$j],
                );
            }
        }

        return $similarity;
    }

    /**
     * Computes cluster size entropy and normalized entropy.
     *
     * @return array{0: float, 1: float}
     */
    private function computeClusterEntropy(
        ClusterResult $result,
        int $totalDocuments,
    ): array {
        $entropy = 0.0;
        $k = \count($result->assignments);

        if ($k <= 1) {
            return [0.0, 0.0];
        }

        foreach ($result->assignments as $keys) {
            $size = \count($keys);
            if (0 === $size) {
                continue;
            }

            $p = $size / $totalDocuments;
            $entropy -= $p * log($p);
        }

        $normalizedEntropy = $entropy / log($k);

        return [$entropy, $normalizedEntropy];
    }

    /**
     * @param array<mixed, float> $values
     */
    private function average(array $values): float
    {
        if ([] === $values) {
            return 0.0;
        }

        return array_sum($values) / \count($values);
    }
}
