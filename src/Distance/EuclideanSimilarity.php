<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Distance;

use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final class EuclideanSimilarity implements SimilarityInterface
{
    public function similarity(VectorInterface $a, VectorInterface $b): float
    {
        $va = $a->toArray();
        $vb = $b->toArray();

        if (\count($va) !== \count($vb)) {
            throw new InvalidArgumentException('Vectors must have the same dimension.');
        }

        $sum = 0.0;

        foreach ($va as $i => $x) {
            $d = $x - $vb[$i];
            $sum += $d * $d;
        }

        $distance = sqrt($sum);

        // Convert distance to similarity (bounded, monotonic)
        return 1.0 / (1.0 + $distance);
    }
}
