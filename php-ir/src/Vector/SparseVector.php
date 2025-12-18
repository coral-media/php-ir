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

final class SparseVector implements VectorInterface
{
    /**
     * @var array<int, float>
     */
    private array $values;

    private int $dimension;

    /**
     * @param array<int, float|int> $values Non-zero values only
     * @param int $dimension Total vector dimension
     */
    public function __construct(array $values, int $dimension)
    {
        if ($dimension <= 0) {
            throw new InvalidArgumentException('Vector dimension must be greater than zero.');
        }

        foreach ($values as $index => $value) {
            if ($index < 0) {
                throw new InvalidArgumentException(
                    'Sparse vector indices must be non-negative integers.',
                );
            }

            if ($index >= $dimension) {
                throw new InvalidArgumentException(
                    'Sparse vector index exceeds declared dimension.',
                );
            }
        }

        $this->values = array_map(
            static fn ($v): float => (float) $v,
            $values,
        );

        $this->dimension = $dimension;
    }

    public function dimension(): int
    {
        return $this->dimension;
    }

    public function get(int $index): float
    {
        if ($index < 0 || $index >= $this->dimension) {
            return 0.0;
        }

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
