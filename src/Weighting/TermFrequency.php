<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

final class TermFrequency
{
    /**
     * @param array<int, int|float> $termCounts index => count
     * @return array<int, float> index => tf
     */
    public function compute(array $termCounts): array
    {
        $total = array_sum($termCounts);

        if (0 === $total) {
            return [];
        }

        $tf = [];
        foreach ($termCounts as $index => $count) {
            if ($count > 0) {
                $tf[$index] = (float) $count / $total;
            }
        }

        return $tf;
    }
}
