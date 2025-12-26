<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords;

final class StopWordsSet
{
    /** @var array<string, true> */
    private array $words;

    /**
     * @param list<string> $words
     */
    public function __construct(array $words)
    {
        $this->words = array_fill_keys($words, true);
    }

    public function contains(string $token): bool
    {
        return isset($this->words[$token]);
    }
}
