<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Serialization;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class VectorCollectionSerializer
{
    /**
     * @return array{
     *     weighting: string,
     *     dimension: int,
     *     documents: array<string, array<int, float>>
     * }
     */
    public function serialize(
        VectorCollection $collection,
        string $weighting,
    ): array {
        $documents = [];

        foreach ($collection as $key => $vector) {
            /** @var VectorInterface $vector */
            $documents[(string) $key] = $vector->toArray();
        }

        return [
            'weighting' => $weighting,
            'dimension' => $collection->dimension(),
            'documents' => $documents,
        ];
    }
}
