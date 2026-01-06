<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Normalization;

use CoralMedia\PhpIr\Feature\Normalization\StopWordsNormalizer;
use CoralMedia\PhpIr\Feature\StopWords\StopWordsSet;
use PHPUnit\Framework\TestCase;

final class StopWordsNormalizerTest extends TestCase
{
    public function testItRemovesStopWordsFromNormalizedText(): void
    {
        $stopWords = new StopWordsSet([
            'el',
            'la',
            'de',
            'y',
            'ser',
        ]);

        $normalizer = new StopWordsNormalizer($stopWords);

        // Simulates:
        // lowercase + accent removal + stemming already applied
        $input  = 'el gato ser rapido y fuerte';
        $output = $normalizer->normalize($input);

        self::assertSame(
            'gato rapido fuerte',
            $output,
        );
    }

    public function testItHandlesEmptyInput(): void
    {
        $stopWords = new StopWordsSet(['el']);
        $normalizer = new StopWordsNormalizer($stopWords);

        self::assertSame('', $normalizer->normalize(''));
    }

    public function testItPreservesOrderAndSpacing(): void
    {
        $stopWords = new StopWordsSet(['a', 'b']);
        $normalizer = new StopWordsNormalizer($stopWords);

        $input  = 'a x b y z';
        $output = $normalizer->normalize($input);

        self::assertSame('x y z', $output);
    }
}
