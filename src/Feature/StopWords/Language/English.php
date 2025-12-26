<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords\Language;

use CoralMedia\PhpIr\Feature\StopWords\StopWordsSet;

final class English
{
    public static function default(): StopWordsSet
    {
        return new StopWordsSet([
            'a', 'e', 'i', 'o', 'u',
            'the', 'an','and','or','but',
            'is','are','was','were',
            'of','to','in','on','for','with',
        ]);
    }
}
