<?php

namespace Gobie\Regex\Tokenizer;

class RegexParser
{

    protected $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function tokenize($regex)
    {
        return $this->parser->parse($regex);
    }
}
