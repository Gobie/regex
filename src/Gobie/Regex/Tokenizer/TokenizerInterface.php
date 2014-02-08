<?php

namespace Gobie\Regex\Tokenizer;

interface TokenizerInterface
{
    const REGEXP = 0,
        PATTERN = 1,
        MODIFIERS = 2;

    public function tokenize($regex);
}
