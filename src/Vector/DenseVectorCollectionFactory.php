<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector;

use CoralMedia\PhpIr\Collection\VectorCollection;
use InvalidArgumentException;

final class DenseVectorCollectionFactory
{
    /**
     * @param iterable<int|string, VectorInterface> $collection
     */
    public static function fromSparse(
        iterable $collection,
        int $dimension,
    ): VectorCollection {
        /** @var array<int|string, DenseVector> $denseVectors */
        $denseVectors = [];

        foreach ($collection as $key => $vector) {
            $dense = array_fill(0, $dimension, 0.0);

            foreach ($vector->toArray() as $i => $v) {
                $dense[(int) $i] = (float) $v;
            }

            $denseVectors[$key] = new DenseVector($dense);
        }

        if ([] === $denseVectors) {
            throw new InvalidArgumentException(
                'Cannot build dense vector collection from empty corpus.',
            );
        }

        return new VectorCollection($denseVectors);
    }
}
