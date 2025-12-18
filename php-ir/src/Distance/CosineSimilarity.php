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

final class CosineSimilarity implements SimilarityInterface
{
    public function similarity(VectorInterface $a, VectorInterface $b): float
    {
        if ($a->dimension() !== $b->dimension()) {
            throw new InvalidArgumentException(
                'Vectors must have the same dimension to compute cosine similarity.',
            );
        }

        $valuesA = $a->toArray();
        $valuesB = $b->toArray();

        // Choose smaller set for dot-product iteration
        if (\count($valuesA) > \count($valuesB)) {
            [$valuesA, $valuesB] = [$valuesB, $valuesA];
            [$a, $b] = [$b, $a];
        }

        $dotProduct = 0.0;

        foreach ($valuesA as $index => $valueA) {
            $dotProduct += $valueA * $b->get($index);
        }

        $normA = 0.0;
        foreach ($a->toArray() as $value) {
            $normA += $value * $value;
        }

        $normB = 0.0;
        foreach ($b->toArray() as $value) {
            $normB += $value * $value;
        }

        if (0.0 === $normA || 0.0 === $normB) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
}
