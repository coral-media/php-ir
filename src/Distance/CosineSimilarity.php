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
        if ($a->dimension() !== $b->dimension()) {
            throw new InvalidArgumentException(
                'Vectors must have the same dimension to compute cosine similarity.',
            );
        }

        // -------------------------------
        // Fast path: sparse × dense
        // -------------------------------
        if ($b instanceof DenseVector) {
            return $this->cosineSparseDense($a, $b);
        }

        if ($a instanceof DenseVector) {
            return $this->cosineSparseDense($b, $a);
        }

        // -------------------------------
        // Fallback: generic (existing logic)
        // -------------------------------
        $valuesA = $a->toArray();
        $valuesB = $b->toArray();

        if (\count($valuesA) > \count($valuesB)) {
            [$valuesA, $valuesB] = [$valuesB, $valuesA];
            [$a, $b] = [$b, $a];
        }

        $dotProduct = 0.0;
        foreach ($valuesA as $index => $valueA) {
            $dotProduct += $valueA * $b->get((int) $index);
        }

        if (false === $this->normalize) {
            return $dotProduct;
        }

        return $dotProduct / ($this->norm($a) * $this->norm($b));
    }

    /**
     * Sparse document × dense centroid cosine similarity.
     */
    private function cosineSparseDense(
        VectorInterface $sparse,
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

        return $dot / (sqrt($normSparse2) * $dense->norm());
    }

    private function norm(VectorInterface $v): float
    {
        $sum = 0.0;
        foreach ($v->toArray() as $value) {
            $sum += $value * $value;
        }

        return sqrt($sum);
    }
}
