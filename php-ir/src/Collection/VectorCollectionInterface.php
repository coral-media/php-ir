<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Collection;

use CoralMedia\PhpIr\Vector\VectorInterface;
use IteratorAggregate;

/**
 * @extends IteratorAggregate<int|string, VectorInterface>
 */
interface VectorCollectionInterface extends IteratorAggregate
{
    /**
     * Number of vectors in the collection.
     */
    public function count(): int;

    /**
     * Returns the vector dimension shared by the collection.
     */
    public function dimension(): int;

    /**
     * Returns a vector by key.
     *
     * @throws \OutOfBoundsException
     */
    public function get(int|string $key): VectorInterface;
}
