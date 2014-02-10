<?php

namespace Gobie\Regex\Parser;

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
