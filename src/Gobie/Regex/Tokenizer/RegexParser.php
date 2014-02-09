<?php

namespace Gobie\Regex\Tokenizer;

class RegexParser
{

    protected $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function parse($regex)
    {
        return $this->parser->parse($regex);
    }
}
