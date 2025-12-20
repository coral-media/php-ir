<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Serialization;

use CoralMedia\PhpIr\Feature\Vocabulary\VocabularyBuilder;

final class VocabularySerializer
{
    /**
     * @return array{
     *     document_count: int,
     *     terms: list<string>
     * }
     */
    public function serialize(VocabularyBuilder $vocabulary): array
    {
        return [
            'document_count' => $vocabulary->documentCount(),
            'terms' => $vocabulary->terms(),
        ];
    }
}
