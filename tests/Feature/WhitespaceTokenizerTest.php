<?php

declare(strict_types=1);

/*
 * (c) Rafael Ernesto Espinosa Santiesteban <rernesto.espinosa@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoralMedia\PhpIr\Tests\Feature;

use CoralMedia\PhpIr\Feature\Tokenizer\WhitespaceTokenizer;
use PHPUnit\Framework\TestCase;

final class WhitespaceTokenizerTest extends TestCase
{
    public function testTokenize(): void
    {
        $tokenizer = new WhitespaceTokenizer();

        $tokens = $tokenizer->tokenize('Crime, Drama & Action!');

        $this->assertSame(
            ['Crime,', 'Drama', '&', 'Action!'],
            $tokens,
        );
    }

    public function testEmptyString(): void
    {
        $tokenizer = new WhitespaceTokenizer();

        $this->assertSame([], $tokenizer->tokenize(''));
    }
}
