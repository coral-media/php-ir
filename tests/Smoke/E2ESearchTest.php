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
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Search\NormalizedSimilaritySearch;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use PHPUnit\Framework\TestCase;

final class E2ESearchTest extends TestCase
{
    public function testFullTextSearchPipeline(): void
    {
        // -------------------------------------------------
        // Documents
        // -------------------------------------------------

        $documents = [
            'doc1' => 'crime drama mafia family',
            'doc2' => 'romantic comedy love story',
            'doc3' => 'crime investigation detective thriller',
        ];

        // -------------------------------------------------
        // Feature extraction
        // -------------------------------------------------

        $tokenizer   = new WhitespaceTokenizer();
        $tfExtractor = new RawTermFrequencyExtractor();
        $vocabulary  = new VocabularyBuilder();

        $documentsTf = [];

        foreach ($documents as $id => $text) {
            $tokens = $tokenizer->tokenize($text);
            $tf     = $tfExtractor->extract($tokens);

            $documentsTf[$id] = $tf;
            $vocabulary->addDocument($tf);
        }

        // -------------------------------------------------
        // Vectorization (terms → indices)
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

        $tfidfBuilder = new TfIdfCorpusBuilder($dimension);
        $corpus       = $tfidfBuilder->build($indexedDocuments);

        $this->assertSame(3, $corpus->count());

        // -------------------------------------------------
        // Query pipeline
        // -------------------------------------------------

        $queryTokens = $tokenizer->tokenize('crime family');
        $queryTf     = $tfExtractor->extract($queryTokens);

        $queryIndexed = $vectorizer
            ->vectorize($queryTf)
            ->toArray()
        ;

        $queryVector = $tfidfBuilder
            ->build(['query' => $queryIndexed])
            ->get('query')
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

        // -------------------------------------------------
        // Assertions
        // -------------------------------------------------

        $this->assertNotEmpty($results);

        $top = $results[0];

        $this->assertSame('doc1', $top->key);
    }
}
