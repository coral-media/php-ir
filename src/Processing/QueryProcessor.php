<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Processing;

use CoralMedia\PhpIr\Feature\Tokenizer\TokenizerInterface;
use CoralMedia\PhpIr\Feature\TermFrequency\TermFrequencyExtractorInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;

final class QueryProcessor
{
    public function __construct(
        private TokenizerInterface $tokenizer,
        private TermFrequencyExtractorInterface $tfExtractor,
        private SparseVectorizer $vectorizer,
    ) {
    }

    public function process(string $query): VectorInterface
    {
        $tokens = $this->tokenizer->tokenize($query);
        $tf     = $this->tfExtractor->extract($tokens);

        return $this->vectorizer->vectorize($tf);
    }
}
