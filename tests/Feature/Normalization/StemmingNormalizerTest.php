<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Normalization;

use CoralMedia\PhpIr\Feature\Normalization\NullStemmer;
use CoralMedia\PhpIr\Feature\Normalization\PorterStemmerAdapter;
use CoralMedia\PhpIr\Feature\Normalization\StemmingNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\StemmerInterface;
use PHPUnit\Framework\TestCase;

final class StemmingNormalizerTest extends TestCase
{
    public function testAdapterAppliesStemFunction(): void
    {
        $adapter = new PorterStemmerAdapter(
            static fn (string $t): string => 'stem',
        );

        $this->assertSame('stem', $adapter->stem('running'));
    }

    public function testReturnsTokenUnchanged(): void
    {
        $stemmer = new NullStemmer();

        $this->assertSame('running', $stemmer->stem('running'));
        $this->assertSame('crime', $stemmer->stem('crime'));
        $this->assertSame('families', $stemmer->stem('families'));
    }

    public function testStemmingReducesVocabularySize(): void
    {
        // Fake stemmer to avoid external dependency
        $stemmer = new class () implements StemmerInterface {
            public function stem(string $token): string
            {
                return match ($token) {
                    'running', 'runs', 'ran' => 'run',
                    default => $token,
                };
            }
        };

        $normalizer = new StemmingNormalizer($stemmer);

        $tokens = [
            'running',
            'runs',
            'ran',
            'runner',
        ];

        $originalVocabularySize = \count(array_unique($tokens));

        $normalized = array_map(
            fn (string $t) => $normalizer->normalize($t),
            $tokens,
        );

        $normalizedVocabularySize = \count(array_unique($normalized));

        $this->assertLessThan(
            $originalVocabularySize,
            $normalizedVocabularySize,
            'Stemming must reduce vocabulary size',
        );
    }
}
