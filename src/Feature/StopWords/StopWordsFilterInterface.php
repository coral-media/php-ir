<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords;

interface StopWordsFilterInterface
{
    /**
     * @param list<string> $tokens
     * @return list<string>
     */
    public function filter(array $tokens): array;
}
