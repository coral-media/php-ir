<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Processing;

use CoralMedia\PhpIr\Processing\CorpusVectorProcessor;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use CoralMedia\PhpIr\Weighting\TfIdfCorpusBuilder;
use CoralMedia\PhpIr\Collection\VectorCollection;
use PHPUnit\Framework\TestCase;

final class CorpusVectorProcessorTest extends TestCase
{
    public function testBuildsVectorCollection(): void
    {
        $vocabulary = new VocabularyBuilder();
        $vocabulary->addDocument(['crime' => 1, 'drama' => 1]);

        $vectorizer = new SparseVectorizer($vocabulary);
        $tfidf      = new TfIdfCorpusBuilder(\count($vocabulary->terms()));

        $processor = new CorpusVectorProcessor($vectorizer, $tfidf);

        $collection = $processor->build([
            'doc1' => ['crime' => 1, 'drama' => 1],
        ]);

        $this->assertInstanceOf(VectorCollection::class, $collection);
        $this->assertSame(1, $collection->count());
    }
}
