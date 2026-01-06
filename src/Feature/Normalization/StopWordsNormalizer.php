<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Normalization;

use CoralMedia\PhpIr\Feature\StopWords\StopWordsSet;

final readonly class StopWordsNormalizer implements TextNormalizerInterface
{
    public function __construct(
        private StopWordsSet $stopwords,
    ) {
    }

    public function normalize(string $text): string
    {
        $tokens = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        if (false === $tokens || [] === $tokens) {
            return '';
        }

        $filtered = array_filter(
            $tokens,
            fn (string $t): bool => !$this->stopwords->contains($t),
        );

        return implode(' ', $filtered);
    }
}
