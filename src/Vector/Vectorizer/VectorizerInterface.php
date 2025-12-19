<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Vector\Vectorizer;

use CoralMedia\PhpIr\Vector\VectorInterface;

interface VectorizerInterface
{
    /**
     * @param array<string,int|float> $features
     */
    public function vectorize(array $features): VectorInterface;
}
