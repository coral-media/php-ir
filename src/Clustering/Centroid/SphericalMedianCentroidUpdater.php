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
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final class SphericalMedianCentroidUpdater implements CentroidUpdaterInterface
{
    private L2Normalizer $normalizer;

    public function __construct(?L2Normalizer $normalizer = null)
    {
        $this->normalizer = $normalizer ?? new L2Normalizer();
    }

    /**
     * @param VectorCollectionInterface $vectors
     * @param list<string> $assignments
     * @return VectorInterface
     */
    public function update(
        VectorCollectionInterface $vectors,
        array $assignments,
    ): VectorInterface {
        if ([] === $assignments) {
            throw new InvalidArgumentException(
                'Cannot compute centroid of an empty cluster.',
            );
        }

        $dimension = $vectors->dimension();

        $valuesByDimension = array_fill(0, $dimension, []);

        foreach ($assignments as $key) {
            $vector = $vectors->get($key);

            if ($vector->dimension() !== $dimension) {
                throw new InvalidArgumentException(
                    'All vectors must share the same dimension.',
                );
            }

            for ($i = 0; $i < $dimension; $i++) {
                $value = $vector->get($i);

                // CRITICAL: ignore zeros (absence, not signal)
                if (0.0 !== $value) {
                    $valuesByDimension[$i][] = $value;
                }
            }
        }

        $medianValues = [];

        foreach ($valuesByDimension as $values) {
            if ([] === $values) {
                // No signal in this dimension
                $medianValues[] = 0.0;
                continue;
            }

            sort($values, SORT_NUMERIC);
            $count = \count($values);
            $mid   = intdiv($count, 2);

            if ($count % 2 === 0) {
                $medianValues[] = ($values[$mid - 1] + $values[$mid]) / 2.0;
            } else {
                $medianValues[] = $values[$mid];
            }
        }

        $centroid = new DenseVector($medianValues);
        $centroid = $this->normalizer->normalize($centroid);

        if ($centroid->dimension() !== $dimension) {
            throw new InvalidArgumentException(
                'Centroid dimension mismatch after median computation.',
            );
        }

        return $centroid;
    }
}
