<?php

namespace Gobie\Regex\Tokenizer;

class TokenFactory implements TokenFactoryInterface
{

    public function createCharToken($char)
    {
        return new Token(array(
            'type'  => 'char',
            'value' => $char
        ));
    }
}
