<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector\Vectorizer;

use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyInterface;
use CoralMedia\PhpIr\Vector\SparseVector;
use CoralMedia\PhpIr\Vector\VectorInterface;

final class SparseVectorizer implements VectorizerInterface
{
    /** @var array<string,int> */
    private array $termIndex = [];

    private int $dimension;

    public function __construct(VocabularyInterface $vocabulary)
    {
        $this->buildIndex($vocabulary);
        $this->dimension = \count($this->termIndex);
    }

    public function vectorize(array $features): VectorInterface
    {
        /** @var array<int,float> $values */
        $values = [];

        foreach ($features as $term => $value) {
            if (!isset($this->termIndex[$term])) {
                continue;
            }

            $index = $this->termIndex[$term];
            $values[$index] = (float) $value;
        }

        return new SparseVector($values, $this->dimension);
    }

    private function buildIndex(VocabularyInterface $vocabulary): void
    {
        $i = 0;

        foreach ($vocabulary->terms() as $term) {
            $this->termIndex[$term] = $i++;
        }
    }
}
