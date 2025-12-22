<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use InvalidArgumentException;

final class SphericalTfIdfCorpusBuilder
{
    private TfIdfCorpusBuilder $tfidfBuilder;
    private VectorCollectionNormalizer $normalizer;

    public function __construct(int $dimension)
    {
        if ($dimension <= 0) {
            throw new InvalidArgumentException('Vector dimension must be greater than zero.');
        }

        $this->tfidfBuilder = new TfIdfCorpusBuilder($dimension);
        $this->normalizer = new VectorCollectionNormalizer(
            new L2Normalizer(),
        );
    }

    /**
     * @param array<int|string, array<int, int|float>> $documents
     */
    public function build(array $documents): VectorCollection
    {
        $corpus = $this->tfidfBuilder->build($documents);

        return $this->normalizer->normalize($corpus);
    }
}
