<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Weighting;

use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use PHPUnit\Framework\TestCase;

final class SphericalTfIdfTest extends TestCase
{
    public function testTfIdfVectorsAreNormalizedToUnitLength(): void
    {
        // -------------------------------------------------
        // Indexed documents (toy but meaningful)
        // -------------------------------------------------
        $documents = [
            'doc1' => [0 => 2, 1 => 1, 2 => 1],
            'doc2' => [0 => 1, 2 => 2],
            'doc3' => [1 => 1, 2 => 1],
        ];

        $dimension = 3;

        // -------------------------------------------------
        // TF-IDF
        // -------------------------------------------------
        $corpus = (new TfIdfCorpusBuilder($dimension))
            ->build($documents)
        ;

        // -------------------------------------------------
        // Spherical normalization
        // -------------------------------------------------
        $normalizer = new VectorCollectionNormalizer(
            new L2Normalizer(),
        );

        $sphericalCorpus = $normalizer->normalize($corpus);

        // -------------------------------------------------
        // Assertion: every vector has ||v|| = 1
        // -------------------------------------------------
        foreach ($sphericalCorpus as $vector) {
            $values = $vector->toArray();

            $norm = sqrt(array_sum(
                array_map(
                    static fn (float $v): float => $v * $v,
                    $values,
                ),
            ));

            $this->assertEqualsWithDelta(
                1.0,
                $norm,
                1e-6,
                'TF-IDF vector must be unit-length after spherical normalization',
            );
        }
    }
}
