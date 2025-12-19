<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Vector\Vectorizer;

use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use CoralMedia\PhpIr\Vector\SparseVector;
use CoralMedia\PhpIr\Vector\Vectorizer\SparseVectorizer;
use PHPUnit\Framework\TestCase;

final class SparseVectorizerTest extends TestCase
{
    public function testVectorizeFromTermFrequencies(): void
    {
        $vocab = new VocabularyBuilder();
        $vocab->addDocument(['crime', 'drama']);
        $vocab->addDocument(['action']);

        $vectorizer = new SparseVectorizer($vocab);

        $vector = $vectorizer->vectorize([
            'crime' => 2,
            'action' => 1,
        ]);

        $this->assertInstanceOf(SparseVector::class, $vector);

        $this->assertSame(
            [
                0 => 2.0, // crime
                2 => 1.0, // action
            ],
            $vector->toArray(),
        );
    }

    public function testIgnoresUnknownTerms(): void
    {
        $vocab = new VocabularyBuilder();
        $vocab->addDocument(['drama']);

        $vectorizer = new SparseVectorizer($vocab);

        $vector = $vectorizer->vectorize([
            'drama' => 1,
            'unknown' => 5,
        ]);

        $this->assertSame(
            [
                0 => 1.0,
            ],
            $vector->toArray(),
        );
    }
}
