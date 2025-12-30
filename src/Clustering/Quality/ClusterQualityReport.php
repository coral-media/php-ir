<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering\Quality;

final readonly class ClusterQualityReport
{
    /**
     * @param array<int, float>  $cohesionPerCluster
     * @param array<string, float> $interCentroidSimilarity
     */
    public function __construct(
        public array $cohesionPerCluster,
        public float $averageCohesion,
        public array $interCentroidSimilarity,
        public float $averageInterCentroidSimilarity,
        public float $qualityScore,
    ) {
    }
}
