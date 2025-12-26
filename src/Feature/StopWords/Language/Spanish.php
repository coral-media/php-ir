<?php

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Feature\StopWords\Language;

use CoralMedia\PhpIr\Feature\StopWords\StopWordsSet;

final class Spanish
{
    public static function default(): StopWordsSet
    {
        return new StopWordsSet([
            'el','la','los','las','un','una','unos','unas',
            'y', 'pero', 'a', 'e', 'i', 'o', 'u',
            'de', 'en','con','por','para','sin','sobre',
            'es','son','era','eran','fue','fueron',
        ]);
    }
}
