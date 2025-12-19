<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Tokenizer;

final class WhitespaceTokenizer implements TokenizerInterface
{
    public function tokenize(string $text): array
    {
        $text = trim($text);

        if ('' === $text) {
            return [];
        }

        return preg_split('/\s+/', $text) ?: [];
    }
}
