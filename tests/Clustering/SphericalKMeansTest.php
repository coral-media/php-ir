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
use CoralMedia\PhpIr\Clustering\SphericalCentroidUpdater;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use PHPUnit\Framework\TestCase;

final class SphericalKMeansTest extends TestCase
{
    public function testCentroidsAreUnitLength(): void
    {
        $collection = new VectorCollection([
            'a' => new DenseVector([1.0, 0.0]),
            'b' => new DenseVector([0.9, 0.1]),
            'c' => new DenseVector([0.0, 1.0]),
            'd' => new DenseVector([0.1, 0.9]),
        ]);

        $kMeans = new KMeans(
            new CosineSimilarity(),
            new KMeansPlusPlusInitializer(new CosineSimilarity()),
            new SphericalCentroidUpdater(),
            maxIterations: 50,
        );

        $result = $kMeans->cluster($collection, 2);

        $this->assertCount(2, $result->centroids);

        foreach ($result->centroids as $centroid) {
            $norm = sqrt(array_sum(
                array_map(
                    static fn (float $v): float => $v * $v,
                    $centroid->toArray(),
                ),
            ));

            $this->assertEqualsWithDelta(
                1.0,
                $norm,
                1e-6,
                'Centroid must be unit length in spherical k-means',
            );
        }
    }

    public function testSphericalTfIdfWithSphericalKMeans(): void
    {
        // -------------------------------------------------
        // Indexed corpus (two clear semantic groups)
        // -------------------------------------------------
        $documents = [
            'crime1' => [0 => 2, 1 => 1], // mafia crime family
            'crime2' => [0 => 1, 1 => 2],
            'crime3' => [0 => 2],

            'romance1' => [2 => 2, 3 => 1], // love romance story
            'romance2' => [2 => 1, 3 => 2],
            'romance3' => [3 => 2],
        ];

        $dimension = 4;

        // -------------------------------------------------
        // TF-IDF
        // -------------------------------------------------
        $sparseCorpus = (new TfIdfCorpusBuilder($dimension))
            ->build($documents)
        ;

        // Densify vectors to fixed dimension
        $corpus = new VectorCollection(
            array_map(
                static function ($vector) use ($dimension) {
                    $dense = array_fill(0, $dimension, 0.0);
                    foreach ($vector->toArray() as $i => $v) {
                        $dense[$i] = $v;
                    }

                    return new DenseVector($dense);
                },
                iterator_to_array($sparseCorpus),
            ),
        );

        // -------------------------------------------------
        // Spherical normalization
        // -------------------------------------------------
        $corpus = (new VectorCollectionNormalizer(
            new L2Normalizer(),
        ))->normalize($corpus);

        // Invariant 1: document vectors are unit length
        foreach ($corpus as $vector) {
            $norm = sqrt(array_sum(
                array_map(
                    static fn (float $v): float => $v * $v,
                    $vector->toArray(),
                ),
            ));

            $this->assertEqualsWithDelta(1.0, $norm, 1e-6);
        }

        // -------------------------------------------------
        // Spherical K-Means
        // -------------------------------------------------
        $kMeans = new KMeans(
            new CosineSimilarity(),
            new KMeansPlusPlusInitializer(new CosineSimilarity()),
            new SphericalCentroidUpdater(),
            maxIterations: 50,
        );

        $result = $kMeans->cluster($corpus, 2);

        // -------------------------------------------------
        // Invariant 2: centroids are unit length
        // -------------------------------------------------
        foreach ($result->centroids as $centroid) {
            $norm = sqrt(array_sum(
                array_map(
                    static fn (float $v): float => $v * $v,
                    $centroid->toArray(),
                ),
            ));

            $this->assertEqualsWithDelta(
                1.0,
                $norm,
                1e-6,
                'Centroid must be unit length in spherical k-means',
            );
        }

        // -------------------------------------------------
        // Invariant 3: clusters are non-empty & separated
        // -------------------------------------------------
        $this->assertCount(2, $result->assignments);

        foreach ($result->assignments as $cluster) {
            $this->assertNotEmpty($cluster);
        }
    }
}
