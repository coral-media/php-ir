<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\TermFrequency;

interface TermFrequencyExtractorInterface
{
    /**
     * Compute term frequencies from a list of tokens.
     *
     * @param list<string> $tokens
     *
     * @return array<string,int>
     */
    public function extract(array $tokens): array;
}
