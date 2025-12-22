<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class ClusterConceptExtractor
{
    /**
     * Extract top-K concept terms from a cluster centroid.
     *
     * @param VectorInterface      $centroid   Normalized centroid vector
     * @param VocabularyInterface  $vocabulary Index → term mapping
     * @param int                 $topK        Number of terms to extract
     *
     * @return array<string, float> term => weight
     */
    public function extract(
        VectorInterface $centroid,
        VocabularyInterface $vocabulary,
        int $topK = 10,
    ): array {
        if ($topK <= 0) {
            return [];
        }

        $terms = [];
        $values = $centroid->toArray();

        foreach ($values as $index => $weight) {
            if (0.0 === $weight) {
                continue;
            }

            $term = $vocabulary->termAt((int) $index);
            $terms[$term] = $weight;
        }

        arsort($terms);

        return \array_slice($terms, 0, $topK, true);
    }
}
