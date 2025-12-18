<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector;

interface VectorInterface
{
    /**
     * Returns the dimensionality of the vector.
     */
    public function dimension(): int;

    /**
     * Returns the value at a given dimension.
     *
     * Implementations MAY return 0.0 for missing values.
     */
    public function get(int $index): float;

    /**
     * Returns the vector as a numeric array.
     *
     * Dense implementations return all values.
     * Sparse implementations may return only non-zero values.
     */
    public function toArray(): array;
}
