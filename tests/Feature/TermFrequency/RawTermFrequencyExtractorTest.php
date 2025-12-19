<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\TermFrequency;

use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use PHPUnit\Framework\TestCase;

final class RawTermFrequencyExtractorTest extends TestCase
{
    public function testExtract(): void
    {
        $extractor = new RawTermFrequencyExtractor();

        $tokens = ['crime', 'drama', 'crime', 'action'];

        $tf = $extractor->extract($tokens);

        $this->assertSame(
            [
                'crime' => 2,
                'drama' => 1,
                'action' => 1,
            ],
            $tf,
        );
    }

    public function testEmptyTokens(): void
    {
        $extractor = new RawTermFrequencyExtractor();

        $this->assertSame([], $extractor->extract([]));
    }
}
