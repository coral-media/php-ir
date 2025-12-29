<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Collection\VectorCollectionInterface;

final class DenseVectorCollectionFactory
{
    public static function fromSparse(
        VectorCollectionInterface $collection,
        int $dimension,
    ): VectorCollection {
        $denseVectors = [];

        foreach ($collection as $key => $vector) {
            $dense = array_fill(0, $dimension, 0.0);

            foreach ($vector->toArray() as $i => $v) {
                $dense[$i] = $v;
            }

            $denseVectors[$key] = new DenseVector($dense);
        }

        return new VectorCollection($denseVectors);
    }
}
