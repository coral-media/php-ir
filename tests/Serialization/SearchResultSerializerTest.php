<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Serialization;

use CoralMedia\PhpIr\Serialization\SearchResultSerializer;
use CoralMedia\PhpIr\Search\SearchResult;
use PHPUnit\Framework\TestCase;

final class SearchResultSerializerTest extends TestCase
{
    public function testSerializesResults(): void
    {
        $serializer = new SearchResultSerializer();

        $results = [
            new SearchResult('doc1', 0.9),
            new SearchResult('doc2', 0.3),
        ];

        $data = $serializer->serialize($results, 'cosine');

        $this->assertSame('cosine', $data['algorithm']);
        $this->assertSame('doc1', $data['results'][0]['key']);
        $this->assertSame(0.9, $data['results'][0]['score']);
    }
}
