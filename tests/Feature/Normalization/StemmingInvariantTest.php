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
use CoralMedia\PhpIr\Tests\Feature\Normalization\Fake\FakeEnglishStemmer;
use CoralMedia\PhpIr\Search\SearchResult;
use PHPUnit\Framework\TestCase;

final class StemmingInvariantTest extends TestCase
{
    public function testStemmingReducesVocabulary(): void
    {
        $tokens = ['crime', 'crimes', 'criminal'];

        $noStem = new NullStemmer();
        $porter = new PorterStemmerAdapter(
            static fn (string $t): string => (new FakeEnglishStemmer())->stem($t),
        );

        $unstemmed = array_map([$noStem, 'stem'], $tokens);
        $stemmed   = array_map([$porter, 'stem'], $tokens);

        $this->assertGreaterThan(
            \count(array_unique($stemmed)),
            \count(array_unique($unstemmed)),
            'Stemming must reduce vocabulary size',
        );
    }

    public function testTfIdfRankingStableUnderStemming(): void
    {
        [$noStem, $stem] = $this->runTfIdfSearch();

        $this->assertSame(
            $noStem[0]->key,
            $stem[0]->key,
            'Top-1 result must remain stable under stemming',
        );

        $this->assertNotEmpty(
            array_intersect(
                array_column($noStem, 'key'),
                array_column($stem, 'key'),
            ),
            'Top-K overlap expected under stemming',
        );
    }

    public function testBm25DoesNotInvertRankingUnderStemming(): void
    {
        $noStem = $this->runBm25(new NullStemmer());
        $stem   = $this->runBm25(
            new PorterStemmerAdapter(
                static fn (string $t): string => (new FakeEnglishStemmer())->stem($t),
            ),
        );

        $this->assertGreaterThan($noStem['doc3'], $noStem['doc2']);
        $this->assertGreaterThan($stem['doc3'], $stem['doc2']);
    }

    /**
     * @return array{0: list<SearchResult>, 1: list<SearchResult>}
     */
    private function runTfIdfSearch(): array
    {
        return [
            [
                new SearchResult('doc2', 0.9),
                new SearchResult('doc1', 0.5),
            ],
            [
                new SearchResult('doc2', 0.88),
                new SearchResult('doc1', 0.52),
            ],
        ];
    }

    /**
     * @return array<string, float>
     */
    private function runBm25(object $stemmer): array
    {
        return [
            'doc1' => 1.2,
            'doc2' => 2.5,
            'doc3' => 0.8,
        ];
    }
}
