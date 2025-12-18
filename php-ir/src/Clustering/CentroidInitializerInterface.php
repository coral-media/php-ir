<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;

interface CentroidInitializerInterface
{
    /**
     * @return list<VectorInterface>
     */
    public function initialize(
        VectorCollectionInterface $collection,
        int $k,
    ): array;
}
