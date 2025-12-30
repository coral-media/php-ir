<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering\Centroid;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final class MedianCentroidUpdater implements CentroidUpdaterInterface
{
    /**
     * @param VectorCollectionInterface $vectors
     * @param list<int|string> $assignments
     * @return VectorInterface
     */
    public function update(
        VectorCollectionInterface $vectors,
        array $assignments,
    ): VectorInterface {
        if ([] === $assignments) {
            throw new InvalidArgumentException('Cannot update centroid from an empty cluster.');
        }

        $dimension = $vectors->dimension();
        $medians = array_fill(0, $dimension, 0.0);

        for ($i = 0; $i < $dimension; $i++) {
            $values = [];

            foreach ($assignments as $key) {
                $values[] = $vectors->get($key)->get($i);
            }

            sort($values);

            $n = \count($values);
            $mid = intdiv($n, 2);

            if (1 === ($n % 2)) {
                $medians[$i] = (float) $values[$mid];
            } else {
                $medians[$i] = ((float) $values[$mid - 1] + (float) $values[$mid]) / 2.0;
            }
        }

        return new DenseVector($medians);
    }
}
