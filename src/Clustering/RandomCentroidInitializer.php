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
use InvalidArgumentException;

final class RandomCentroidInitializer implements CentroidInitializerInterface
{
    public function initialize(
        VectorCollectionInterface $collection,
        int $k,
    ): array {
        if ($k <= 0 || $k > $collection->count()) {
            throw new InvalidArgumentException('Invalid number of clusters.');
        }

        $vectors = iterator_to_array($collection);
        $keys = array_keys($vectors);

        shuffle($keys);

        $centroids = [];
        for ($i = 0; $i < $k; $i++) {
            $centroids[] = $vectors[$keys[$i]];
        }

        return $centroids;
    }
}
