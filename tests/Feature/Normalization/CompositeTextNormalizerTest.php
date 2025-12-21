<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Normalization;

use CoralMedia\PhpIr\Feature\Normalization\CompositeTextNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use CoralMedia\PhpIr\Feature\Normalization\LowercaseNormalizer;
use PHPUnit\Framework\TestCase;

final class CompositeTextNormalizerTest extends TestCase
{
    public function testAppliesNormalizersInOrder(): void
    {
        $normalizer = new CompositeTextNormalizer([
            new LowercaseNormalizer(),
            new AccentNormalizer(),
        ]);

        $this->assertSame(
            'accion nino',
            $normalizer->normalize('Acción Niño'),
        );
    }
}
