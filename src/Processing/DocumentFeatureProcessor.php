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
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;

final class DocumentFeatureProcessor
{
    public function __construct(
        private TokenizerInterface $tokenizer,
        private TermFrequencyExtractorInterface $tfExtractor,
        private VocabularyBuilder $vocabulary,
    ) {
    }

    /**
     * @param array<string,string> $documents
     * @return array<string,array<string,int>>
     */
    public function process(array $documents): array
    {
        $result = [];

        foreach ($documents as $id => $text) {
            $tokens = $this->tokenizer->tokenize($text);
            $tf     = $this->tfExtractor->extract($tokens);

            $this->vocabulary->addDocument($tf);
            $result[$id] = $tf;
        }

        return $result;
    }
}
