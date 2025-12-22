<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Clustering;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class SphericalCentroidUpdater implements CentroidUpdaterInterface
{
    private L2Normalizer $normalizer;

    public function __construct(?L2Normalizer $normalizer = null)
    {
        $this->normalizer = $normalizer ?? new L2Normalizer();
    }

    public function update(
        VectorCollectionInterface $vectors,
        array $assignments,
    ): VectorInterface {
        $sum = [];

        foreach ($assignments as $key) {
            $values = $vectors->get($key)->toArray();
            foreach ($values as $i => $v) {
                $sum[$i] = ($sum[$i] ?? 0.0) + $v;
            }
        }

        $centroid = new DenseVector($sum);

        return $this->normalizer->normalize($centroid);
    }
}
