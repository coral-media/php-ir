<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Smoke;

use CoralMedia\PhpIr\Clustering\CentroidInitializerInterface;
use CoralMedia\PhpIr\Collection\VectorCollectionInterface;
use CoralMedia\PhpIr\Feature\Normalization\TextNormalizerInterface;
use CoralMedia\PhpIr\Feature\TermFrequency\TermFrequencyExtractorInterface;
use CoralMedia\PhpIr\Feature\Tokenizer\TokenizerInterface;
use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyInterface;
use CoralMedia\PhpIr\Normalization\VectorNormalizerInterface;
use CoralMedia\PhpIr\Vector\VectorInterface;
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use CoralMedia\PhpIr\Vector\Vectorizer\VectorizerInterface;
use PHPUnit\Framework\TestCase;

final class ContractsTest extends TestCase
{
    public function testContractsExist(): void
    {
        $this->assertTrue(interface_exists(VectorInterface::class));
        $this->assertTrue(interface_exists(SimilarityInterface::class));
        $this->assertTrue(interface_exists(CentroidInitializerInterface::class));
        $this->assertTrue(interface_exists(VectorCollectionInterface::class));
        $this->assertTrue(interface_exists(TermFrequencyExtractorInterface::class));
        $this->assertTrue(interface_exists(TokenizerInterface::class));
        $this->assertTrue(interface_exists(VocabularyInterface::class));
        $this->assertTrue(interface_exists(VectorNormalizerInterface::class));
        $this->assertTrue(interface_exists(VectorizerInterface::class));
        $this->assertTrue(interface_exists(TextNormalizerInterface::class));
    }
}
