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

interface SimilarityInterface
{
    /**
     * Computes similarity between two vectors.
     *
     * Higher score means more similar.
     */
    public function similarity(
        VectorInterface $a,
        VectorInterface $b,
    ): float;
}
