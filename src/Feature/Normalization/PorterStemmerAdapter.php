<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Normalization;

use InvalidArgumentException;

final class PorterStemmerAdapter implements StemmerInterface
{
    /**
     * @var callable(string):string
     */
    private $stemCallable;

    /**
     * @param callable(string):string $stemCallable
     */
    public function __construct(callable $stemCallable)
    {
        $this->stemCallable = $stemCallable;
    }

    public function stem(string $token): string
    {
        return ($this->stemCallable)($token);
    }
}
