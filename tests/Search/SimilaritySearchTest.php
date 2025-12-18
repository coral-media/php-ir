<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Search;

use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\DenseVector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SimilaritySearchTest extends TestCase
{
    public function testReturnsTopKInOrder(): void
    {
        $corpus = new VectorCollection([
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([0, 1]),
            'c' => new DenseVector([1, 1]),
        ]);

        $query = new DenseVector([1, 0]);

        $search = new SimilaritySearch(new CosineSimilarity());
        $results = $search->search($query, $corpus, 2);

        $this->assertCount(2, $results);
        $this->assertSame('a', $results[0]->key);
        $this->assertSame('c', $results[1]->key);

        $this->assertEqualsWithDelta(1.0, $results[0]->score, 1e-10);
        $this->assertTrue($results[0]->score >= $results[1]->score);
    }

    public function testTopKMustBePositive(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $corpus = new VectorCollection(['a' => new DenseVector([1])]);
        $query = new DenseVector([1]);

        (new SimilaritySearch(new CosineSimilarity()))->search($query, $corpus, 0);
    }

    public function testQueryDimensionMustMatchCorpus(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $corpus = new VectorCollection(['a' => new DenseVector([1, 0])]);
        $query = new DenseVector([1]);

        (new SimilaritySearch(new CosineSimilarity()))->search($query, $corpus, 1);
    }
}
