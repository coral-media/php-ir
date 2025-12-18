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
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;
use InvalidArgumentException;

final readonly class SimilaritySearch
{
    public function __construct(
        private SimilarityInterface $similarity,
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
        if ($topK <= 0) {
            throw new InvalidArgumentException('topK must be greater than zero.');
        }

        if ($query->dimension() !== $corpus->dimension()) {
            throw new InvalidArgumentException(
                'Query vector dimension must match corpus dimension.',
            );
        }

        $results = [];

        foreach ($corpus as $key => $vector) {
            if (null !== $filter && \in_array($key, $filter->excludeKeys, true)) {
                continue;
            }

            $score = $this->similarity->similarity($query, $vector);

            if (null !== $filter && null !== $filter->minScore && $score < $filter->minScore) {
                continue;
            }

            $results[] = new SearchResult($key, $score);
        }

        usort(
            $results,
            static fn (SearchResult $a, SearchResult $b): int =>
                $b->score <=> $a->score,
        );

        return \array_slice($results, 0, $topK);
    }
}
