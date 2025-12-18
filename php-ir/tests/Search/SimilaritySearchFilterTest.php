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
use CoralMedia\PhpIr\Search\SearchFilter;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class SimilaritySearchFilterTest extends TestCase
{
    public function testExcludeKeys(): void
    {
        $corpus = new VectorCollection([
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([1, 0]),
        ]);

        $query = new DenseVector([1, 0]);

        $filter = new SearchFilter(null, ['a']);

        $results = (new SimilaritySearch(new CosineSimilarity()))
            ->search($query, $corpus, 10, $filter)
        ;

        $this->assertCount(1, $results);
        $this->assertSame('b', $results[0]->key);
    }

    public function testMinScore(): void
    {
        $corpus = new VectorCollection([
            'a' => new DenseVector([1, 0]),
            'b' => new DenseVector([0, 1]),
        ]);

        $query = new DenseVector([1, 0]);

        $filter = new SearchFilter(0.9);

        $results = (new SimilaritySearch(new CosineSimilarity()))
            ->search($query, $corpus, 10, $filter)
        ;

        $this->assertCount(1, $results);
        $this->assertSame('a', $results[0]->key);
    }
}
