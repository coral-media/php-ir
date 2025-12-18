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
use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Normalization\VectorCollectionNormalizer;
use CoralMedia\PhpIr\Search\NormalizedSimilaritySearch;
use CoralMedia\PhpIr\Search\SimilaritySearch;
use CoralMedia\PhpIr\Vector\DenseVector;
use PHPUnit\Framework\TestCase;

final class NormalizedSimilaritySearchTest extends TestCase
{
    public function testNormalizedSearchReturnsCorrectOrder(): void
    {
        $corpus = new VectorCollection([
            'a' => new DenseVector([10, 0]),
            'b' => new DenseVector([0, 1]),
            'c' => new DenseVector([1, 1]),
        ]);

        $query = new DenseVector([1, 0]);

        $search = new NormalizedSimilaritySearch(
            new SimilaritySearch(new CosineSimilarity()),
            new L2Normalizer(),
            new VectorCollectionNormalizer(new L2Normalizer()),
        );

        $results = $search->search($query, $corpus, 3);

        $this->assertSame('a', $results[0]->key);
        $this->assertSame('c', $results[1]->key);
    }
}
