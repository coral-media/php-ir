<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Normalization\Fake;

/**
 * Fake English stemmer for invariant testing.
 *
 * This is NOT a linguistic stemmer.
 * It exists solely to enforce term conflation invariants
 * (vocabulary reduction, ranking stability).
 */
final class FakeEnglishStemmer
{
    public function stem(string $token): string
    {
        return match ($token) {
            'crimes', 'criminal' => 'crime',
            default => $token,
        };
    }
}
