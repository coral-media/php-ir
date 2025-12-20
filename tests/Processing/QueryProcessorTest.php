<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Processing;

use CoralMedia\PhpIr\Processing\QueryProcessor;
use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Vector\VectorInterface;
use PHPUnit\Framework\TestCase;

final class QueryProcessorTest extends TestCase
{
    public function testProcessesQueryIntoVector(): void
    {
        $vocabulary = new VocabularyBuilder();
        $vocabulary->addDocument(['crime' => 1]);

        $processor = new QueryProcessor(
            new WhitespaceTokenizer(),
            new RawTermFrequencyExtractor(),
            new SparseVectorizer($vocabulary),
        );

        $vector = $processor->process('crime');

        $this->assertInstanceOf(VectorInterface::class, $vector);
        $this->assertNotEmpty($vector->toArray());
    }
}
