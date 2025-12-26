<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\StopWords;

use CoralMedia\PhpIr\Feature\StopWords\Language\English;
use CoralMedia\PhpIr\Feature\StopWords\Language\Spanish;
use CoralMedia\PhpIr\Feature\StopWords\StopWordsFilter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StopWordsLanguageTest extends TestCase
{
    /**
     * @param StopWordsFilter $filter
     * @param list<string>    $tokens
     * @param list<string>    $expected
     */
    #[DataProvider('languageProvider')]
    public function testStopWordsAreRemovedCorrectly(
        StopWordsFilter $filter,
        array $tokens,
        array $expected,
    ): void {
        $this->assertSame(
            $expected,
            $filter->filter($tokens),
        );
    }

    /**
     * @return iterable<string, array{
     *   0: StopWordsFilter,
     *   1: list<string>,
     *   2: list<string>
     * }>
     */
    public static function languageProvider(): iterable
    {
        yield 'english' => [
            new StopWordsFilter(English::default()),
            ['the', 'crime', 'and', 'the', 'family'],
            ['crime', 'family'],
        ];

        yield 'spanish' => [
            new StopWordsFilter(Spanish::default()),
            ['la', 'familia', 'y', 'el', 'crimen'],
            ['familia', 'crimen'],
        ];
    }
}
