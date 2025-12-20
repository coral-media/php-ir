<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

use InvalidArgumentException;

final class Bm25
{
    private float $k1;
    private float $b;

    /**
     * Create a BM25 scorer.
     *
     * @param float $k1 Controls term-frequency saturation.
     *                  Typical values are in the range [1.2, 2.0].
     *                  Higher values make term frequency matter more.
     *
     * @param float $b  Controls document length normalization.
     *                  0.0 disables length normalization,
     *                  1.0 applies full normalization.
     *                  Typical value is 0.75.
     */
    public function __construct(float $k1 = 1.5, float $b = 0.75)
    {
        if ($k1 <= 0.0) {
            throw new InvalidArgumentException('k1 must be > 0.');
        }

        if ($b < 0.0 || $b > 1.0) {
            throw new InvalidArgumentException('b must be in range [0,1].');
        }

        $this->k1 = $k1;
        $this->b  = $b;
    }

    /**
     * Scores documents against a query using BM25.
     *
     * @param array<int|string, array<int, int>> $documents
     *        docId => [termIndex => termFrequency]
     *
     * @param array<int, int> $documentFrequencies
     *        termIndex => documentFrequency
     *
     * @param array<int, int> $queryTerms
     *        termIndex => termFrequency (binary or raw)
     *
     * @return array<int|string, float>
     *        docId => score (sorted descending)
     */
    public function score(
        array $documents,
        array $documentFrequencies,
        array $queryTerms,
    ): array {
        if ([] === $documents) {
            return [];
        }

        $documentCount = \count($documents);

        // Compute document lengths
        $documentLengths = [];
        foreach ($documents as $docId => $terms) {
            $documentLengths[$docId] = array_sum($terms);
        }

        $averageDocumentLength =
            array_sum($documentLengths) / $documentCount;

        $scores = [];

        foreach ($documents as $docId => $terms) {
            $score = 0.0;
            $dl    = $documentLengths[$docId];

            foreach ($queryTerms as $termIndex => $queryTf) {
                if (!isset($terms[$termIndex])) {
                    continue;
                }

                $tf = $terms[$termIndex];
                $df = $documentFrequencies[$termIndex] ?? 0;

                // IDF (BM25 standard)
                $idf = log(
                    (($documentCount - $df + 0.5) / ($df + 0.5)) + 1.0,
                );

                $normalization =
                    $tf + $this->k1 * (
                        1.0 - $this->b +
                        $this->b * ($dl / $averageDocumentLength)
                    );

                $score += $idf *
                    (($tf * ($this->k1 + 1.0)) / $normalization);
            }

            if ($score > 0.0) {
                $scores[$docId] = $score;
            }
        }

        arsort($scores);

        return $scores;
    }
}
