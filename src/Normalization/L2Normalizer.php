<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Normalization;

use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\SparseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class L2Normalizer implements VectorNormalizerInterface
{
    public function normalize(VectorInterface $vector): VectorInterface
    {
        $values = $vector->toArray();

        $sumSquares = 0.0;
        foreach ($values as $value) {
            $sumSquares += $value * $value;
        }

        if (0.0 === $sumSquares) {
            return $vector;
        }

        $norm = sqrt($sumSquares);

        $normalized = [];
        foreach ($values as $index => $value) {
            $normalized[$index] = $value / $norm;
        }

        // Preserve vector type
        if ($vector instanceof SparseVector) {
            return new SparseVector($normalized, $vector->dimension());
        }

        return new DenseVector($normalized);
    }
}
