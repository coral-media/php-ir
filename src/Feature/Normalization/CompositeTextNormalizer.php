<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Normalization;

final readonly class CompositeTextNormalizer implements TextNormalizerInterface
{
    /**
     * @param list<TextNormalizerInterface> $normalizers
     */
    public function __construct(
        private array $normalizers,
    ) {
    }

    public function normalize(string $text): string
    {
        foreach ($this->normalizers as $normalizer) {
            $text = $normalizer->normalize($text);
        }

        return $text;
    }
}
