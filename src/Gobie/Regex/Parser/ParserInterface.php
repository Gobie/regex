<?php

namespace Gobie\Regex\Parser;

interface ParserInterface
{
    const REGEXP = 0,
        PATTERN = 1,
        MODIFIERS = 2;

    public function parse($regex);
}
