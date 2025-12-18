<?php

declare(strict_types=1);

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