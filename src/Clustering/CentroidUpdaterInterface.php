<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;

interface CentroidUpdaterInterface
{
    /**
     * @param VectorCollectionInterface $vectors
     * @param list<int|string> $assignments
     */
    public function update(
        VectorCollectionInterface $vectors,
        array $assignments,
    ): VectorInterface;
}
