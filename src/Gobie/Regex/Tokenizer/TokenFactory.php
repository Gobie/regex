<?php

namespace Gobie\Regex\Tokenizer;

class TokenFactory implements TokenFactoryInterface
{

    public function createCharToken($char)
    {
        return new TokenNode(array(
            'type'  => 'char',
            'value' => $char
        ));
    }
}
