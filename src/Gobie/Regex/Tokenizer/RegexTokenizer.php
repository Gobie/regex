<?php

namespace Gobie\Regex\Tokenizer;

class RegexTokenizer
{

    protected $tokenizer;

    public function __construct(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    public function tokenize($regex)
    {
        return $this->tokenizer->tokenize($regex);
    }
}
