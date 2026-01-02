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
        // -------------------------------------------------
        // Fast path: sparse × dense (document × centroid)
        // -------------------------------------------------
        if ($b instanceof DenseVector) {
            return $this->cosineSparseDense($a, $b);
        }

        if ($a instanceof DenseVector) {
            return $this->cosineSparseDense($b, $a);
        }

        // -------------------------------------------------
        // Dense × dense (strict dimensionality)
        // -------------------------------------------------
        if ($a->dimension() !== $b->dimension()) {
            throw new InvalidArgumentException(
                'Vectors must have the same dimension to compute cosine similarity.',
            );
        }

        // -------------------------------------------------
        // Generic fallback (sparse × sparse or dense × dense)
        // -------------------------------------------------
        $valuesA = $a->toArray();
        $valuesB = $b->toArray();

        // Iterate over the smaller set
        if (\count($valuesA) > \count($valuesB)) {
            [$valuesA, $valuesB] = [$valuesB, $valuesA];
            [$a, $b] = [$b, $a];
        }

        $dotProduct = 0.0;
        foreach ($valuesA as $index => $valueA) {
            $dotProduct += (float) $valueA * $b->get((int) $index);
        }

        if (false === $this->normalize) {
            return $dotProduct;
        }

        return $dotProduct / ($this->norm($a) * $this->norm($b));
    }

    /**
     * Cosine similarity between a sparse vector and a dense centroid.
     *
     * @param VectorInterface $sparse   Sparse document vector
     * @param DenseVector    $dense    Dense centroid vector
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
            $sum += (float) $value * (float) $value;
        }

        return sqrt($sum);
    }
}
