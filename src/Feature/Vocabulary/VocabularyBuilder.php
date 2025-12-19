<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Vocabulary;

final class VocabularyBuilder implements VocabularyInterface
{
    /** @var array<string,int> */
    private array $documentFrequencies = [];

    private int $documentCount = 0;

    /**
     * Add a document represented as term frequencies or tokens.
     *
     * @param array<string,int>|list<string> $terms
     */
    public function addDocument(array $terms): void
    {
        ++$this->documentCount;

        /** @var list<string> $uniqueTerms */
        $uniqueTerms = $this->extractUniqueTerms($terms);

        foreach ($uniqueTerms as $term) {
            $this->documentFrequencies[$term] =
                ($this->documentFrequencies[$term] ?? 0) + 1;
        }
    }

    public function documentCount(): int
    {
        return $this->documentCount;
    }

    public function documentFrequency(string $term): int
    {
        return $this->documentFrequencies[$term] ?? 0;
    }

    /**
     * @return list<string>
     */
    public function terms(): array
    {
        return array_keys($this->documentFrequencies);
    }

    /**
     * Normalize input into a list of unique string terms.
     *
     * @param array<string,int>|list<string> $terms
     *
     * @return list<string>
     */
    private function extractUniqueTerms(array $terms): array
    {
        if ($this->isAssociative($terms)) {
            /** @var list<string> */
            return array_keys($terms);
        }

        /** @var list<string> */
        return array_unique($terms);
    }

    /**
     * @param array<mixed,mixed> $array
     */
    private function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, \count($array) - 1);
    }
}
