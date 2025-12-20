<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Processing;

use CoralMedia\PhpIr\Processing\SearchProcessor;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Distance\CosineSimilarity;
use CoralMedia\PhpIr\Collection\VectorCollection;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class SearchProcessorTest extends TestCase
{
    public function testDelegatesSearch(): void
    {
        $search = new SearchProcessor(
            new SimilaritySearch(new CosineSimilarity()),
        );

        $corpus = new VectorCollection([
            'doc1' => new DenseVector([1, 0]),
            'doc2' => new DenseVector([0, 1]),
        ]);

        $query = new DenseVector([1, 0]);

        $results = $search->search($query, $corpus);

        $this->assertNotEmpty($results);
        $this->assertSame('doc1', $results[0]->key);
    }
}
