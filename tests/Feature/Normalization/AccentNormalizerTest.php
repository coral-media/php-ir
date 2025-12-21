<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Normalization;

use CoralMedia\PhpIr\Feature\Normalization\AccentNormalizer;
use PHPUnit\Framework\TestCase;

final class AccentNormalizerTest extends TestCase
{
    public function testNormalizesAccents(): void
    {
        $normalizer = new AccentNormalizer();

        $this->assertSame(
            'accion nino',
            $normalizer->normalize('acción niño'),
        );
    }
}
