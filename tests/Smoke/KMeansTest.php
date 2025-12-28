<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Smoke;

use CoralMedia\PhpIr\Clustering\KMeansFactory;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\CompositeTextNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\LowercaseNormalizer;
use CoralMedia\PhpIr\Feature\StopWords\Language\English;
use CoralMedia\PhpIr\Feature\StopWords\Language\Spanish;
use CoralMedia\PhpIr\Feature\StopWords\StopWordsFilter;
use CoralMedia\PhpIr\Feature\StopWords\StopWordsFilterInterface;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Tokenizer\RegexTokenizer;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\SphericalTfIdfCorpusBuilder;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @phpstan-type MovieDocument array{
 *   id: string,
 *   fields: array{
 *     title: string,
 *     description: string
 *   }
 * }
 *
 * @phpstan-type MovieCollection array{
 *   documents: list<MovieDocument>
 * }
 */
final class KMeansTest extends TestCase
{
    /**
     * @param MovieCollection $data
     */
    #[DataProvider('corpusProvider')]
    public function testKMeans(
        array $data,
        StopWordsFilterInterface $stopWordsFilter,
    ): void {
        $tokenizer   = new RegexTokenizer();
        $tfExtractor = new RawTermFrequencyExtractor();
        $vocabulary  = new VocabularyBuilder();

        $textNormalizer = new CompositeTextNormalizer([
            new LowercaseNormalizer(),
            new AccentNormalizer(),
        ]);

        /** @var array<string, array<string,int>> $documentsTf */
        $documentsTf = [];

        foreach ($data['documents'] as $doc) {
            $text = $doc['fields']['title'] . ' ' . $doc['fields']['description'];

            $tokens = $stopWordsFilter->filter(
                $tokenizer->tokenize(
                    $textNormalizer->normalize($text),
                ),
            );

            $tf = $tfExtractor->extract($tokens);

            $documentsTf[$doc['id']] = $tf;
            $vocabulary->addDocument($tf);
        }

        $vectorizer = new SparseVectorizer($vocabulary);

        /** @var array<string, array<int, float>> $indexedDocuments */
        $indexedDocuments = [];

        foreach ($documentsTf as $id => $tf) {
            $indexedDocuments[$id] = $vectorizer
                ->vectorize($tf)
                ->toArray()
            ;
        }

        $this->assertNotEmpty($indexedDocuments);

        $dimension = \count($vocabulary->terms());

        $sparseCorpus = (new SphericalTfIdfCorpusBuilder($dimension))
            ->build($indexedDocuments)
        ;

        $corpus = new VectorCollection(
            array_map(
                static function ($vector) use ($dimension): DenseVector {
                    $dense = array_fill(0, $dimension, 0.0);

                    foreach ($vector->toArray() as $i => $v) {
                        $dense[$i] = $v;
                    }

                    return new DenseVector($dense);
                },
                iterator_to_array($sparseCorpus),
            ),
        );

        $medianResult = KMeansFactory::sphericalMedian(200)->cluster($corpus, 4);
        $meanResult   = KMeansFactory::spherical(200)->cluster($corpus, 4);

        // Minimal semantic invariant: median ≠ mean in presence of skew
        $this->assertNotEquals($medianResult, $meanResult);
    }

    /**
     * @return iterable<string, array{
     *   0: MovieCollection,
     *   1: StopWordsFilterInterface
     * }>
     *
     * @throws JsonException
     */
    public static function corpusProvider(): iterable
    {
        $root = \dirname(__DIR__, 2);

        $enPath = $root . '/fixtures/en.movies.collection.json';
        $esPath = $root . '/fixtures/es.movies.collection.json';

        if (!is_file($enPath)) {
            throw new RuntimeException("English fixture not found: $enPath");
        }

        if (!is_file($esPath)) {
            throw new RuntimeException("Spanish fixture not found: $esPath");
        }

        $enJson = file_get_contents($enPath);
        if (false === $enJson) {
            throw new RuntimeException("Failed to read English fixture: $enPath");
        }

        /** @var MovieCollection $enData */
        $enData = json_decode(
            $enJson,
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        $esJson = file_get_contents($esPath);
        if (false === $esJson) {
            throw new RuntimeException("Failed to read Spanish fixture: $esPath");
        }

        /** @var MovieCollection $esData */
        $esData = json_decode(
            $esJson,
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        yield 'english' => [
            $enData,
            new StopWordsFilter(English::default()),
        ];

        yield 'spanish' => [
            $esData,
            new StopWordsFilter(Spanish::default()),
        ];
    }
}
