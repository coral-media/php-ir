<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Distance;

use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\SparseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final readonly class CosineSimilarity implements SimilarityInterface
{
    public function __construct(
        private bool $normalize = true,
    ) {
    }

    public function similarity(VectorInterface $a, VectorInterface $b): float
    {
        // Fast path: sparse × dense
        if ($a instanceof SparseVector && $b instanceof DenseVector) {
            return $this->cosineSparseDense($a, $b);
        }

        if ($b instanceof SparseVector && $a instanceof DenseVector) {
            return $this->cosineSparseDense($b, $a);
        }

        // Generic fallback (sparse × sparse OR dense × dense)
        $valuesA = $a->toArray();
        $valuesB = $b->toArray();

        if (\count($valuesA) > \count($valuesB)) {
            [$valuesA, $valuesB] = [$valuesB, $valuesA];
            [$a, $b] = [$b, $a];
        }

        $dot = 0.0;
        foreach ($valuesA as $i => $v) {
            $dot += (float) $v * $b->get((int) $i);
        }

        if (false === $this->normalize) {
            return $dot;
        }

        $normA = $this->norm($a);
        $normB = $this->norm($b);

        if (0.0 === $normA || 0.0 === $normB) {
            return 0.0;
        }

        return $dot / ($normA * $normB);
    }

    private function cosineSparseDense(
        SparseVector $sparse,
        DenseVector $dense,
    ): float {
        $dot = 0.0;
        $normSparse2 = 0.0;

        foreach ($sparse->toArray() as $i => $v) {
            $fv = (float) $v;
            $dot += $fv * $dense->get((int) $i);
            $normSparse2 += $fv * $fv;
        }

        if (false === $this->normalize) {
            return $dot;
        }

        if (0.0 === $normSparse2) {
            return 0.0;
        }

        $normDense = $dense->norm();
        if (0.0 === $normDense) {
            return 0.0;
        }

        return $dot / (sqrt($normSparse2) * $normDense);
    }

    private function norm(VectorInterface $v): float
    {
        $sum = 0.0;
        foreach ($v->toArray() as $value) {
            $fv = (float) $value;
            $sum += $fv * $fv;
        }

        return 0.0 === $sum ? 0.0 : sqrt($sum);
    }
}
