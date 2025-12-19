<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Tokenizer;

interface TokenizerInterface
{
    /**
     * Tokenize a text into terms.
     *
     * @return list<string>
     */
    public function tokenize(string $text): array;
}
