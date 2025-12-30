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
use CoralMedia\PhpIr\Clustering\Quality\ClusterQualityCalculator;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\CompositeTextNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\LowercaseNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\NullStemmer;
use CoralMedia\PhpIr\Feature\Normalization\PorterStemmerAdapter;
use CoralMedia\PhpIr\Feature\Normalization\StemmingNormalizer;
use CoralMedia\PhpIr\Feature\StopWords\Language\English;
use CoralMedia\PhpIr\Feature\StopWords\Language\Spanish;
use CoralMedia\PhpIr\Feature\StopWords\StopWordsFilter;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Tokenizer\RegexTokenizer;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\DenseVectorCollectionFactory;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\SphericalTfIdfCorpusBuilder;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class SphericalKMedianQualityTest extends TestCase
{
    #[DataProvider('corpusProvider')]
    public function testMedianVsMean(
        VectorCollection $corpus,
    ): void {
        $quality = new ClusterQualityCalculator(
            new CosineSimilarity(),
        );
        $meanWins = 0;
        $medianWins = 0;
        for ($i = 0; $i < 100; ++$i) {
            $meanResult = KMeansFactory::spherical(150)
                ->cluster($corpus, 2)
            ;

            $medianResult = KMeansFactory::sphericalMedian(150)
                ->cluster($corpus, 2)
            ;

            $meanQuality = $quality->evaluate($corpus, $meanResult);
            $medianQuality = $quality->evaluate($corpus, $medianResult);
            if ($meanQuality->qualityScore > $medianQuality->qualityScore) {
                $meanWins++;
            } else {
                $medianWins++;
            }
        }
        $this->assertGreaterThanOrEqual($meanWins, $medianWins);
    }

    /**
     * @return iterable<string, array{0: VectorCollection}>
     * @throws JsonException
     */
    public static function corpusProvider(): iterable
    {
        $root = \dirname(__DIR__, 2);

        $paths = [
            'english' => [
                $root . '/fixtures/en.movies.collection.json',
                new StopWordsFilter(English::default()),
            ],
            'spanish' => [
                $root . '/fixtures/es.movies.collection.json',
                new StopWordsFilter(Spanish::default()),
            ],
        ];

        foreach ($paths as $label => [$path, $stopWords]) {
            if (!is_file($path)) {
                throw new RuntimeException("Fixture not found: $path");
            }

            $json = file_get_contents($path);
            if (false === $json) {
                throw new RuntimeException("Failed to read fixture: $path");
            }

            /** @var array{
             *   documents: list<array{
             *     id: string,
             *     fields: array{title: string, description: string}
             *   }>
             * } $data
             */
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $tokenizer   = new RegexTokenizer();
            $tfExtractor = new RawTermFrequencyExtractor();
            $vocabulary  = new VocabularyBuilder();

            $normalizer = new CompositeTextNormalizer([
                new LowercaseNormalizer(),
                new AccentNormalizer(),
                new StemmingNormalizer(
                    new PorterStemmerAdapter(static fn (string $t): string => (new NullStemmer())->stem($t)),
                ),
            ]);

            /** @var array<string, array<string, int>> $documentsTf */
            $documentsTf = [];

            foreach ($data['documents'] as $doc) {
                $text = $doc['fields']['title'] . ' ' . $doc['fields']['description'];

                $tokens = $stopWords->filter(
                    $tokenizer->tokenize(
                        $normalizer->normalize($text),
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

            $dimension = \count($vocabulary->terms());

            // -------------------------------------------------
            // Spherical TF-IDF
            // -------------------------------------------------
            $sphericalTfIdf = (new SphericalTfIdfCorpusBuilder($dimension))
                ->build($indexedDocuments)
            ;

            // -------------------------------------------------
            // Densify for clustering
            // -------------------------------------------------
            $corpus = DenseVectorCollectionFactory::fromSparse(
                $sphericalTfIdf,
                $dimension,
            );

            yield $label => [$corpus];
        }
    }
}
