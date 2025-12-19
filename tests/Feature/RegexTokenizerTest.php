<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature\Tokenizer;

use CoralMedia\PhpIr\Feature\Tokenizer\RegexTokenizer;
use PHPUnit\Framework\TestCase;

final class RegexTokenizerTest extends TestCase
{
    public function testTokenize(): void
    {
        $tokenizer = new RegexTokenizer();

        $tokens = $tokenizer->tokenize('Crime, Drama & Action!');

        $this->assertSame(
            ['crime', 'drama', 'action'],
            $tokens,
        );
    }

    public function testEmptyString(): void
    {
        $tokenizer = new RegexTokenizer();

        $this->assertSame([], $tokenizer->tokenize(''));
    }
}
