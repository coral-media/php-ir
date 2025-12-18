<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Smoke;

use CoralMedia\PhpIr\Vector\VectorInterface;
use CoralMedia\PhpIr\Distance\SimilarityInterface;
use PHPUnit\Framework\TestCase;

final class ContractsTest extends TestCase
{
    public function testContractsExist(): void
    {
        $this->assertTrue(interface_exists(VectorInterface::class));
        $this->assertTrue(interface_exists(SimilarityInterface::class));
    }
}
