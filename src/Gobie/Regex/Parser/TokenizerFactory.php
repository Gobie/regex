<?php

namespace Gobie\Regex\Parser;

class TokenizerFactory implements TokenizerFactoryInterface
{

    public function create($data, $encoding = 'UTF-8')
    {
        return new Tokenizer($data, $encoding);
    }
}
