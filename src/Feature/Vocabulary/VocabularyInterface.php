<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Vocabulary;

use InvalidArgumentException;

interface VocabularyInterface
{
    /**
     * Total number of documents in the corpus.
     */
    public function documentCount(): int;

    /**
     * Document frequency of a term (df).
     */
    public function documentFrequency(string $term): int;

    /**
     * All known terms in the vocabulary.
     *
     * @return list<string>
     */
    public function terms(): array;

    /**
     * Return the term corresponding to a given vocabulary index.
     *
     * @param int $index Zero-based vocabulary index
     * @return string The term at the given index
     * @throws InvalidArgumentException If the index is out of bounds
     */
    public function termAt(int $index): string;
}
