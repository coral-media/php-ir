<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\Vocabulary;

final readonly class VocabularyStatistics
{
    public function __construct(
        private VocabularyInterface $vocabulary,
    ) {
    }

    public function idfValue(int $termIndex): float
    {
        $term = $this->vocabulary->termAt($termIndex);
        $df   = $this->vocabulary->documentFrequency($term);
        $n    = $this->vocabulary->documentCount();

        return log(($n + 1) / ($df + 1)) + 1.0;
    }

    /**
     * @return array<int, float>
     */
    public function idfVector(): array
    {
        $idf = [];
        $n   = $this->vocabulary->documentCount();

        foreach ($this->vocabulary->terms() as $i => $term) {
            $df = $this->vocabulary->documentFrequency($term);
            $idf[$i] = log(($n + 1) / ($df + 1)) + 1.0;
        }

        return $idf;
    }
}
