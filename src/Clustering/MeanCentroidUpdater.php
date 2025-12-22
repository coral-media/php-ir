<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class MeanCentroidUpdater implements CentroidUpdaterInterface
{
    public function update(
        VectorCollectionInterface $vectors,
        array $assignments,
    ): VectorInterface {
        $sum = [];
        $count = 0;

        foreach ($assignments as $key) {
            $values = $vectors->get($key)->toArray();
            foreach ($values as $i => $v) {
                $sum[$i] = ($sum[$i] ?? 0.0) + $v;
            }
            $count++;
        }

        foreach ($sum as $i => $v) {
            $sum[$i] = $v / $count;
        }

        return new DenseVector($sum);
    }
}
