<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Normalization;

use CoralMedia\PhpIr\Normalization\L2Normalizer;
use CoralMedia\PhpIr\Vector\DenseVector;
use CoralMedia\PhpIr\Vector\SparseVector;
use PHPUnit\Framework\TestCase;

final class L2NormalizerTest extends TestCase
{
    public function testDenseNormalization(): void
    {
        $v = new DenseVector([3, 4]);
        $n = (new L2Normalizer())->normalize($v);

        $this->assertEqualsWithDelta(0.6, $n->get(0), 1e-10);
        $this->assertEqualsWithDelta(0.8, $n->get(1), 1e-10);
    }

    public function testSparseNormalization(): void
    {
        $v = new SparseVector([1 => 3, 3 => 4], 5);
        $n = (new L2Normalizer())->normalize($v);

        $this->assertEqualsWithDelta(0.6, $n->get(1), 1e-10);
        $this->assertEqualsWithDelta(0.8, $n->get(3), 1e-10);
    }

    public function testZeroVectorReturnsSame(): void
    {
        $v = new SparseVector([], 3);
        $n = (new L2Normalizer())->normalize($v);

        $this->assertSame($v, $n);
    }
}
