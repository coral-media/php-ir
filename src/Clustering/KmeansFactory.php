<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Distance\CosineSimilarity;

final class KMeansFactory
{
    public static function classic(int $maxIterations = 100): KMeans
    {
        return new KMeans(
            new CosineSimilarity(),
            new KMeansPlusPlusInitializer(new CosineSimilarity()),
            new MeanCentroidUpdater(),
            $maxIterations,
        );
    }

    public static function spherical(int $maxIterations = 100): KMeans
    {
        return new KMeans(
            new CosineSimilarity(),
            new KMeansPlusPlusInitializer(new CosineSimilarity()),
            new SphericalCentroidUpdater(),
            $maxIterations,
        );
    }
}
