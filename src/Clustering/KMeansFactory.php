<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Clustering\Centroid\MeanCentroidUpdater;
use CoralMedia\PhpIr\Clustering\Centroid\MedianCentroidUpdater;
use CoralMedia\PhpIr\Clustering\Centroid\SphericalCentroidUpdater;
use CoralMedia\PhpIr\Clustering\Centroid\SphericalMedianCentroidUpdater;
use CoralMedia\PhpIr\Clustering\Initializer\KMeansPlusPlusInitializer;
use CoralMedia\PhpIr\Clustering\Initializer\SphericalKMeansPlusPlusInitializer;
use CoralMedia\PhpIr\Distance\CosineSimilarity;

final class KMeansFactory
{
    public static function classic(int $maxIterations = 100): KMeans
    {
        $similarity = new CosineSimilarity();

        return new KMeans(
            $similarity,
            new KMeansPlusPlusInitializer($similarity),
            new MeanCentroidUpdater(),
            $maxIterations,
        );
    }

    public static function classicMedian(int $maxIterations = 100): KMeans
    {
        $similarity = new CosineSimilarity();

        return new KMeans(
            $similarity,
            new KMeansPlusPlusInitializer($similarity),
            new MedianCentroidUpdater(),
            $maxIterations,
        );
    }

    public static function spherical(int $maxIterations = 100): KMeans
    {
        $similarity = new CosineSimilarity(false);

        return new KMeans(
            $similarity,
            new SphericalKMeansPlusPlusInitializer($similarity),
            new SphericalCentroidUpdater(),
            $maxIterations,
        );
    }

    public static function sphericalMedian(int $maxIterations = 100): KMeans
    {
        $similarity = new CosineSimilarity(false);

        return new KMeans(
            $similarity,
            new SphericalKMeansPlusPlusInitializer($similarity),
            new SphericalMedianCentroidUpdater(),
            $maxIterations,
        );
    }
}
