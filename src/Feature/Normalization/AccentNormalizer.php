<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Normalization;

final class AccentNormalizer implements TextNormalizerInterface
{
    /**
     * @var array<string,string>
     */
    private array $replacements = [
        'á' => 'a',
        'à' => 'a',
        'é' => 'e',
        'è' => 'e',
        'í' => 'i',
        'ì' => 'i',
        'ó' => 'o',
        'ò' => 'o',
        'ö' => 'o',
        'ú' => 'u',
        'ù' => 'u',
        'ü' => 'u',
        'ç' => 'c',
        'ñ' => 'n',
    ];

    public function normalize(string $text): string
    {
        return strtr($text, $this->replacements);
    }
}
