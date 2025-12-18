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
use InvalidArgumentException;
use OutOfBoundsException;
use Traversable;

final class VectorCollection implements VectorCollectionInterface
{
    /**
     * @var array<int|string, VectorInterface>
     */
    private array $vectors;

    private int $dimension;

    /**
     * @param array<int|string, VectorInterface> $vectors
     */
    public function __construct(array $vectors)
    {
        if ([] === $vectors) {
            throw new InvalidArgumentException('Vector collection cannot be empty.');
        }

        $first = reset($vectors);
        $this->dimension = $first->dimension();

        foreach ($vectors as $key => $vector) {
            if ($vector->dimension() !== $this->dimension) {
                throw new InvalidArgumentException(
                    'All vectors in a collection must share the same dimension.',
                );
            }
        }

        $this->vectors = $vectors;
    }

    public function count(): int
    {
        return \count($this->vectors);
    }

    public function dimension(): int
    {
        return $this->dimension;
    }

    public function get(int|string $key): VectorInterface
    {
        if (!\array_key_exists($key, $this->vectors)) {
            throw new OutOfBoundsException('Vector key not found in collection.');
        }

        return $this->vectors[$key];
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->vectors);
    }
}
