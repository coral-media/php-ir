<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Weighting;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Vector\SparseVector;
use InvalidArgumentException;

final readonly class TfIdfCorpusBuilder
{

    public function __construct(private int $dimension)
    {
        if ($dimension <= 0) {
            throw new InvalidArgumentException('Vector dimension must be greater than zero.');
        }
    }

    /**
     * @param array<int|string, array<int, int|float>> $documents
     */
    public function build(array $documents): VectorCollection
    {
        if ([] === $documents) {
            throw new InvalidArgumentException('Corpus cannot be empty.');
        }

        $documentCount = \count($documents);
        $documentFrequencies = [];

        // 1. Compute document frequencies
        foreach ($documents as $terms) {
            foreach (array_keys($terms) as $index) {
                $documentFrequencies[$index] =
                    ($documentFrequencies[$index] ?? 0) + 1;
            }
        }

        // 2. Compute IDF
        $idf = (new InverseDocumentFrequency())
            ->compute($documentFrequencies, $documentCount)
        ;

        $tfComputer = new TermFrequency();
        $transformer = new TfIdfTransformer();

        $vectors = [];

        // 3. Build TF-IDF vectors
        foreach ($documents as $key => $terms) {
            $tf = $tfComputer->compute($terms);

            $vectors[$key] = $transformer->transform(
                $tf,
                $idf,
                $this->dimension,
            );
        }

        return new VectorCollection($vectors);
    }
}
