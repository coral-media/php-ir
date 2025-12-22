<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Smoke;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\CompositeTextNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\LowercaseNormalizer;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use PHPUnit\Framework\TestCase;

final class E2ESphericalDotProductSearchTest extends TestCase
{
    public function testEndToEndSearchUsesSphericalDotProduct(): void
    {
        $path = __DIR__ . '/../../fixtures/en.movies.collection.json';
        $json = file_get_contents($path);
        $this->assertNotFalse($json);

        /** @var array{documents: list<array{id: string, fields: array{title: string, description: string}}>} $data */
        $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('documents', $data);
        $this->assertCount(10, $data['documents']);

        // -------------------------------------------------
        // Feature extraction
        // -------------------------------------------------
        $tokenizer = new WhitespaceTokenizer();
        $tfExtractor = new RawTermFrequencyExtractor();
        $vocabulary = new VocabularyBuilder();

        $textNormalizer = new CompositeTextNormalizer([
            new LowercaseNormalizer(),
            new AccentNormalizer(),
        ]);

        /** @var array<string, array<string,int>> $documentsTf */
        $documentsTf = [];

        foreach ($data['documents'] as $doc) {
            $text = $doc['fields']['title'] . ' ' . $doc['fields']['description'];
            $tokens = $tokenizer->tokenize($textNormalizer->normalize($text));
            $tf = $tfExtractor->extract($tokens);

            $documentsTf[$doc['id']] = $tf;
            $vocabulary->addDocument($tf);
        }

        // -------------------------------------------------
        // Vectorization (terms -> indices)
        // -------------------------------------------------
        $vectorizer = new SparseVectorizer($vocabulary);
        $dimension = \count($vocabulary->terms());

        /** @var array<string, array<int, float>> $indexedDocuments */
        $indexedDocuments = [];

        foreach ($documentsTf as $id => $tf) {
            $indexedDocuments[$id] = $vectorizer->vectorize($tf)->toArray();
        }

        // -------------------------------------------------
        // Query (vectorized in the same vocabulary)
        // -------------------------------------------------
        $queryText = 'mafia crime family';

        $queryTf = $tfExtractor->extract(
            $tokenizer->tokenize($textNormalizer->normalize($queryText)),
        );

        /** @var array<int, float> $indexedQuery */
        $indexedQuery = $vectorizer->vectorize($queryTf)->toArray();

        // -------------------------------------------------
        // TF-IDF corpus (documents + query in ONE build => consistent IDF)
        // -------------------------------------------------
        $all = $indexedDocuments;
        $all['__query__'] = $indexedQuery;

        $tfidfAll = (new TfIdfCorpusBuilder($dimension))->build($all);

        // -------------------------------------------------
        // Densify + spherical normalization (unit vectors)
        // -------------------------------------------------
        $denseAll = $this->densifyCollection($tfidfAll, $dimension);

        $denseAll = (new VectorCollectionNormalizer(
            new L2Normalizer(),
        ))->normalize($denseAll);

        /** @var VectorInterface $queryVector */
        $queryVector = $denseAll->get('__query__');

        // Rebuild corpus without query
        $denseCorpusArray = iterator_to_array($denseAll);
        unset($denseCorpusArray['__query__']);

        $corpus = new VectorCollection($denseCorpusArray);

        // -------------------------------------------------
        // Search: dot product mode (spherical cosine)
        // -------------------------------------------------
        $search = new SimilaritySearch(
            new CosineSimilarity(normalize: false),
        );

        $results = $search->search($queryVector, $corpus);

        $this->assertNotEmpty($results);

        $topIds = array_map(
            static fn ($r) => $r->key,
            \array_slice($results, 0, 3),
        );

        $this->assertContains('the_godfather', $topIds);
        $this->assertContains('the_godfather_part_ii', $topIds);
    }

    private function densifyCollection(VectorCollectionInterface $collection, int $dimension): VectorCollection
    {
        $dense = [];

        foreach ($collection as $key => $vector) {
            $values = array_fill(0, $dimension, 0.0);

            foreach ($vector->toArray() as $i => $v) {
                $values[(int) $i] = (float) $v;
            }

            $dense[$key] = new DenseVector($values);
        }

        return new VectorCollection($dense);
    }
}
