<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Processing;

use CoralMedia\PhpIr\Processing\DocumentFeatureProcessor;
use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use CoralMedia\PhpIr\Feature\TermFrequency\RawTermFrequencyExtractor;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use PHPUnit\Framework\TestCase;

final class DocumentFeatureProcessorTest extends TestCase
{
    public function testProcessesDocumentsIntoTermFrequencies(): void
    {
        $vocabulary = new VocabularyBuilder();

        $processor = new DocumentFeatureProcessor(
            new WhitespaceTokenizer(),
            new RawTermFrequencyExtractor(),
            $vocabulary,
        );

        $tf = $processor->process([
            'doc1' => 'crime drama crime',
        ]);

        $this->assertSame(
            ['crime' => 2, 'drama' => 1],
            $tf['doc1'],
        );

        $this->assertSame(1, $vocabulary->documentCount());
        $this->assertSame(1, $vocabulary->documentFrequency('drama'));
        $this->assertSame(1, $vocabulary->documentFrequency('crime'));
    }
}
