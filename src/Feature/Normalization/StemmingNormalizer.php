<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Normalization;

final class StemmingNormalizer implements TextNormalizerInterface
{
    public function __construct(
        private StemmerInterface $stemmer,
    ) {
    }

    public function normalize(string $text): string
    {
        return implode(' ', array_map(
            fn (string $t) => $this->stemmer->stem($t),
            explode(' ', $text),
        ));
    }
}
