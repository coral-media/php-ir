<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

use CoralMedia\PhpIr\Vector\SparseVector;

final class TfIdfTransformer
{
    /**
     * @param array<int, float> $tf
     * @param array<int, float> $idf
     * @param int $dimension
     * @return SparseVector
     */
    public function transform(array $tf, array $idf, int $dimension): SparseVector
    {
        $weights = [];

        foreach ($tf as $index => $tfValue) {
            if (isset($idf[$index])) {
                $weight = $tfValue * $idf[$index];
                if ($weight > 0.0) {
                    $weights[$index] = $weight;
                }
            }
        }

        return new SparseVector($weights, $dimension);
    }
}
