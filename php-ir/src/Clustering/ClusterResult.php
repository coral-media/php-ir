<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Vector\VectorInterface;

final readonly class ClusterResult
{
    /**
     * @param array<int, list<int|string>> $assignments cluster => document keys
     * @param array<int, VectorInterface> $centroids
     */
    public function __construct(
        public array $assignments,
        public array $centroids,
    ) {
    }
}
