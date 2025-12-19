<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\TermFrequency;

final class RawTermFrequencyExtractor implements TermFrequencyExtractorInterface
{
    public function extract(array $tokens): array
    {
        $frequencies = [];

        foreach ($tokens as $token) {
            $frequencies[$token] = ($frequencies[$token] ?? 0) + 1;
        }

        return $frequencies;
    }
}
