<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Processing;

use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Vector\VectorInterface;
use CoralMedia\PhpIr\Search\SearchResult;

final class SearchProcessor
{
    public function __construct(
        private SimilaritySearch $search,
    ) {
    }

    /**
     * @return list<SearchResult>
     */
    public function search(
        VectorInterface $query,
        VectorCollection $corpus,
    ): array {
        return $this->search->search($query, $corpus);
    }
}
