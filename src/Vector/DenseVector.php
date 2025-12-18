<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector;

use InvalidArgumentException;

final class DenseVector implements VectorInterface
{
    /**
     * @var array<int, float>
     */
    private array $values;

    /**
     * @param array<int, float|int> $values
     */
    public function __construct(array $values)
    {
        if ([] === $values) {
            throw new InvalidArgumentException('Vector values cannot be empty.');
        }

        $this->values = array_map(
            static fn ($v): float => (float) $v,
            array_values($values),
        );
    }

    public function dimension(): int
    {
        return \count($this->values);
    }

    public function get(int $index): float
    {
        return $this->values[$index] ?? 0.0;
    }

    /**
     * @return array<int, float>
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
