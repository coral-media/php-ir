<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Weighting;

use CoralMedia\PhpIr\Weighting\TermFrequency;
use CoralMedia\PhpIr\Weighting\InverseDocumentFrequency;
use CoralMedia\PhpIr\Weighting\TfIdfTransformer;
use PHPUnit\Framework\TestCase;

final class TfIdfTest extends TestCase
{
    public function testTfIdfComputation(): void
    {
        // Document term counts
        $termCounts = [0 => 2, 1 => 1];

        // Corpus stats
        $documentFrequencies = [0 => 3, 1 => 1];
        $documentCount = 4;

        $tf = (new TermFrequency())->compute($termCounts);
        $idf = (new InverseDocumentFrequency())->compute(
            $documentFrequencies,
            $documentCount,
        );

        $vector = (new TfIdfTransformer())->transform($tf, $idf, 5);

        $this->assertSame(5, $vector->dimension());
        $this->assertArrayHasKey(0, $vector->toArray());
        $this->assertArrayHasKey(1, $vector->toArray());
    }
}
