<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Smoke;

use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\CompositeTextNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\LowercaseNormalizer;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Search\NormalizedSimilaritySearch;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\Bm25;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use PHPUnit\Framework\TestCase;

final class EndToEndMovieSearchTest extends TestCase
{
    public function testSearchOverMovieCollectionJson(): void
    {
        $path = __DIR__ . '/../../fixtures/en.movies.collection.json';
        $json = file_get_contents($path);

        $this->assertNotFalse($json);

        /** @var array{
         *   documents: list<array{
         *     id: string,
         *     fields: array{
         *       title: string,
         *       description: string
         *     }
         *   }>
         * } $data
         */
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('documents', $data);
        $this->assertCount(10, $data['documents']);

        // -------------------------------------------------
        // Feature extraction
        // -------------------------------------------------
        $tokenizer   = new WhitespaceTokenizer();
        $tfExtractor = new RawTermFrequencyExtractor();
        $vocabulary  = new VocabularyBuilder();
        $textNormalizer = new CompositeTextNormalizer([
            new LowercaseNormalizer(),
            new AccentNormalizer(),
        ]);

        $documentsTf = [];

        foreach ($data['documents'] as $doc) {
            $text = $doc['fields']['title'] . ' ' . $doc['fields']['description'];

            $tokens = $tokenizer->tokenize($textNormalizer->normalize($text));
            $tf     = $tfExtractor->extract($tokens);

            $documentsTf[$doc['id']] = $tf;
            $vocabulary->addDocument($tf);
        }

        // -------------------------------------------------
        // Vectorization
        // -------------------------------------------------
        $vectorizer = new SparseVectorizer($vocabulary);
        $dimension  = \count($vocabulary->terms());

        $indexedDocuments = [];

        foreach ($documentsTf as $id => $tf) {
            $indexedDocuments[$id] = $vectorizer
                ->vectorize($tf)
                ->toArray()
            ;
        }

        // -------------------------------------------------
        // TF-IDF corpus
        // -------------------------------------------------
        $corpus = (new TfIdfCorpusBuilder($dimension))
            ->build($indexedDocuments)
        ;

        $this->assertSame(10, $corpus->count());

        // -------------------------------------------------
        // Query
        // -------------------------------------------------
        $queryTf = $tfExtractor->extract(
            $tokenizer->tokenize($textNormalizer->normalize('mafia crime family')),
        );

        $queryVector = (new TfIdfCorpusBuilder($dimension))
            ->build([
                '__query__' => $vectorizer
                    ->vectorize($queryTf)
                    ->toArray(),
            ])
            ->get('__query__')
        ;

        // -------------------------------------------------
        // Search
        // -------------------------------------------------
        $search = new NormalizedSimilaritySearch(
            new SimilaritySearch(new CosineSimilarity()),
            new L2Normalizer(),
            new VectorCollectionNormalizer(new L2Normalizer()),
        );

        $results = $search->search($queryVector, $corpus);

        $this->assertNotEmpty($results);

        // -------------------------------------------------
        // Assertions
        // -------------------------------------------------
        $topIds = array_map(
            static fn ($r) => $r->key,
            \array_slice($results, 0, 3),
        );

        $this->assertContains('the_godfather', $topIds);
        $this->assertContains('the_godfather_part_ii', $topIds);
    }

    public function testSearchOverMovieCollectionUsingBm25(): void
    {
        $path = __DIR__ . '/../../fixtures/en.movies.collection.json';
        $json = file_get_contents($path);
        $this->assertNotFalse($json);

        /** @var array{
         *   documents: list<array{
         *     id: string,
         *     fields: array{
         *       title: string,
         *       description: string
         *     }
         *   }>
         * } $data
         */
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        $tokenizer   = new WhitespaceTokenizer();
        $tfExtractor = new RawTermFrequencyExtractor();
        $vocabulary  = new VocabularyBuilder();
        $textNormalizer = new CompositeTextNormalizer([
            new LowercaseNormalizer(),
            new AccentNormalizer(),
        ]);

        /** @var array<string, array<string, int>> $documentsTf */
        $documentsTf = [];

        foreach ($data['documents'] as $doc) {
            $text = $doc['fields']['title'] . ' ' . $doc['fields']['description'];

            $tokens = $tokenizer->tokenize($textNormalizer->normalize($text));
            $tf     = $tfExtractor->extract($tokens);

            $documentsTf[$doc['id']] = $tf;
            $vocabulary->addDocument($tf);
        }

        // -------------------------------------------------
        // Build term → index map (same order as vectorizer)
        // -------------------------------------------------
        $termIndex = [];
        foreach ($vocabulary->terms() as $i => $term) {
            $termIndex[$term] = $i;
        }

        // -------------------------------------------------
        // Indexed documents
        // -------------------------------------------------
        /** @var array<string, array<int, int>> $indexedDocuments */
        $indexedDocuments = [];

        foreach ($documentsTf as $docId => $tf) {
            foreach ($tf as $term => $freq) {
                $indexedDocuments[$docId][$termIndex[$term]] = $freq;
            }
        }

        // -------------------------------------------------
        // Document frequencies (indexed)
        // -------------------------------------------------
        /** @var array<int, int> $documentFrequencies */
        $documentFrequencies = [];

        foreach ($termIndex as $term => $index) {
            $documentFrequencies[$index] = $vocabulary->documentFrequency($term);
        }

        // -------------------------------------------------
        // Query (indexed)
        // -------------------------------------------------
        $queryTf = $tfExtractor->extract(
            $tokenizer->tokenize($textNormalizer->normalize('mafia crime family')),
        );

        /** @var array<int, int> $queryIndexed */
        $queryIndexed = [];

        foreach ($queryTf as $term => $freq) {
            if (isset($termIndex[$term])) {
                $queryIndexed[$termIndex[$term]] = $freq;
            }
        }

        // -------------------------------------------------
        // BM25 scoring
        // -------------------------------------------------
        $bm25 = new Bm25();

        $scores = $bm25->score(
            $indexedDocuments,
            $documentFrequencies,
            $queryIndexed,
        );

        $this->assertNotEmpty($scores);

        arsort($scores);

        $topIds = array_keys(\array_slice($scores, 0, 3, true));

        $this->assertContains('the_godfather', $topIds);
        $this->assertContains('the_godfather_part_ii', $topIds);
    }
}
