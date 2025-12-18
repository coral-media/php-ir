<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

final class InverseDocumentFrequency
{
    /**
     * @param array<int, int> $documentFrequencies index => df
     * @param int $documentCount total documents
     *
     * @return array<int, float> index => idf
     */
    public function compute(array $documentFrequencies, int $documentCount): array
    {
        $idf = [];

        foreach ($documentFrequencies as $index => $df) {
            $idf[$index] = log(($documentCount + 1) / ($df + 1)) + 1.0;
        }

        return $idf;
    }
}
