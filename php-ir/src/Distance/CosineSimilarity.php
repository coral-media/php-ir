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

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < $a->dimension(); $i++) {
            $va = $a->get($i);
            $vb = $b->get($i);

            $dotProduct += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }

        if (0.0 === $normA || 0.0 === $normB) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
}
