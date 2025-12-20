<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Processing;

use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use CoralMedia\PhpIr\Collection\VectorCollection;

final class CorpusVectorProcessor
{
    public function __construct(
        private SparseVectorizer $vectorizer,
        private TfIdfCorpusBuilder $tfIdfBuilder,
    ) {
    }

    /**
     * @param array<string,array<string,int>> $termFrequencies
     */
    public function build(array $termFrequencies): VectorCollection
    {
        $indexed = [];

        foreach ($termFrequencies as $id => $tf) {
            $indexed[$id] = $this->vectorizer
                ->vectorize($tf)
                ->toArray()
            ;
        }

        return $this->tfIdfBuilder->build($indexed);
    }
}
