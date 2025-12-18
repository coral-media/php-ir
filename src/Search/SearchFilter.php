<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Search;

final readonly class SearchFilter
{
    /**
     * @param float|null $minScore Minimum similarity score (inclusive)
     * @param array<int|string> $excludeKeys Keys to exclude from results
     */
    public function __construct(
        public ?float $minScore = null,
        public array  $excludeKeys = [],
    ) {
    }
}
