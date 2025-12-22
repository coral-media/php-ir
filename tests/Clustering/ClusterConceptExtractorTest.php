<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Clustering;

use CoralMedia\PhpIr\Clustering\ClusterConceptExtractor;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class ClusterConceptExtractorTest extends TestCase
{
    public function testExtractsTopWeightedConceptTerms(): void
    {
        // Vocabulary: index → term
        $vocabulary = new VocabularyBuilder();
        $vocabulary->addDocument(['crime' => 1, 'mafia' => 1, 'family' => 1]);

        // Simulated centroid (already normalized)
        $centroid = new DenseVector([
            0 => 0.6, // crime
            1 => 0.4, // mafia
            2 => 0.1, // family
        ]);

        $extractor = new ClusterConceptExtractor();

        $concepts = $extractor->extract($centroid, $vocabulary, topK: 2);

        $this->assertSame(
            ['crime', 'mafia'],
            array_keys($concepts),
        );

        $this->assertGreaterThan(
            $concepts['mafia'],
            $concepts['crime'],
        );
    }
}
