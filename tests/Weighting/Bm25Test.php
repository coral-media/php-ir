<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Weighting;

use CoralMedia\PhpIr\Weighting\Bm25;
use PHPUnit\Framework\TestCase;

final class Bm25Test extends TestCase
{
    public function testRanksDocumentsContainingQueryHigher(): void
    {
        // -------------------------------------------------
        // Corpus (indexed term frequencies)
        // -------------------------------------------------

        $documents = [
            'doc1' => [0 => 3, 1 => 1], // contains term 0 strongly
            'doc2' => [1 => 2],        // does NOT contain term 0
        ];

        // Document frequencies
        $documentFrequencies = [
            0 => 1,
            1 => 2,
        ];

        // Query: single term (term index 0)
        $queryTerms = [
            0 => 1,
        ];

        $bm25 = new Bm25();

        $scores = $bm25->score(
            $documents,
            $documentFrequencies,
            $queryTerms,
        );

        // -------------------------------------------------
        // Assertions
        // -------------------------------------------------

        $this->assertNotEmpty($scores);

        $rankedDocumentIds = array_keys($scores);

        // Document containing the query term must rank first
        $this->assertSame('doc1', $rankedDocumentIds[0]);
    }
}
