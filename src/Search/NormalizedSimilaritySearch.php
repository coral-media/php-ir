<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Search;

use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Normalization\VectorNormalizerInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class NormalizedSimilaritySearch
{
    public function __construct(
        private readonly SimilaritySearch $search,
        private readonly VectorNormalizerInterface $normalizer,
        private readonly VectorCollectionNormalizer $collectionNormalizer,
    ) {
    }

    /**
     * @return list<SearchResult>
     */
    public function search(
        VectorInterface $query,
        VectorCollectionInterface $corpus,
        int $topK = 10,
        ?SearchFilter $filter = null,
    ): array {
        $normalizedQuery = $this->normalizer->normalize($query);
        $normalizedCorpus = $this->collectionNormalizer->normalize($corpus);

        return $this->search->search(
            $normalizedQuery,
            $normalizedCorpus,
            $topK,
            $filter,
        );
    }
}
