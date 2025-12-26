<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords;

final readonly class StopWordsFilter implements StopWordsFilterInterface
{
    public function __construct(
        private StopWordsSet $stopwords,
    ) {
    }

    public function filter(array $tokens): array
    {
        return array_values(array_filter(
            $tokens,
            fn (string $t): bool => !$this->stopwords->contains($t),
        ));
    }
}
