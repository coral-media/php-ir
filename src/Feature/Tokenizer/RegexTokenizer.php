<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Tokenizer;

final readonly class RegexTokenizer implements TokenizerInterface
{
    public function __construct(private string $regex = '/[^\p{L}\p{N}]+/u')
    {
    }

    public function tokenize(string $text): array
    {
        $text = mb_strtolower($text);

        // Split on any non-letter / non-digit sequence
        $tokens = preg_split($this->regex, $text);

        if (false === $tokens) {
            return [];
        }

        // Remove empty tokens
        return array_values(
            array_filter($tokens, static fn (string $t): bool => '' !== $t),
        );
    }
}
