<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Vocabulary;

use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;
use PHPUnit\Framework\TestCase;

final class VocabularyBuilderTest extends TestCase
{
    public function testDocumentFrequencyFromTokens(): void
    {
        $vocab = new VocabularyBuilder();

        $vocab->addDocument(['crime', 'drama', 'crime']);
        $vocab->addDocument(['drama', 'action']);

        $this->assertSame(2, $vocab->documentCount());
        $this->assertSame(1, $vocab->documentFrequency('crime'));
        $this->assertSame(2, $vocab->documentFrequency('drama'));
        $this->assertSame(1, $vocab->documentFrequency('action'));
        $this->assertSame(0, $vocab->documentFrequency('comedy'));
    }

    public function testDocumentFrequencyFromTermFrequencies(): void
    {
        $vocab = new VocabularyBuilder();

        $vocab->addDocument([
            'crime' => 3,
            'drama' => 1,
        ]);

        $vocab->addDocument([
            'drama' => 2,
            'action' => 1,
        ]);

        $this->assertSame(2, $vocab->documentCount());
        $this->assertSame(1, $vocab->documentFrequency('crime'));
        $this->assertSame(2, $vocab->documentFrequency('drama'));
        $this->assertSame(1, $vocab->documentFrequency('action'));
    }
}
