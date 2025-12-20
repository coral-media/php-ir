<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Serialization;

use CoralMedia\PhpIr\Search\SearchResult;

final class SearchResultSerializer
{
    /**
     * @param list<SearchResult> $results
     *
     * @return array{
     *     algorithm: string,
     *     results: list<array{
     *         key: string,
     *         score: float
     *     }>
     * }
     */
    public function serialize(array $results, string $algorithm): array
    {
        return [
            'algorithm' => $algorithm,
            'results' => array_map(
                static fn (SearchResult $r): array => [
                    'key' => (string) $r->key,
                    'score' => $r->score,
                ],
                $results,
            ),
        ];
    }
}
