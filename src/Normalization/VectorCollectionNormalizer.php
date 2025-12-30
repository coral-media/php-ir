<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Normalization;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Collection\VectorCollectionInterface;

final readonly class VectorCollectionNormalizer
{
    public function __construct(
        private VectorNormalizerInterface $normalizer,
    ) {
    }

    public function normalize(VectorCollectionInterface $collection): VectorCollection
    {
        $normalized = [];

        foreach ($collection as $key => $vector) {
            $normalized[$key] = $this->normalizer->normalize($vector);
        }

        return new VectorCollection($normalized);
    }
}
