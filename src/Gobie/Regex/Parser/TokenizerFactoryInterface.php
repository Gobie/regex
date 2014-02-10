<?php

namespace Gobie\Regex\Parser;

interface TokenizerFactoryInterface
{

    /**
     * Creates Tokenizer.
     *
     * @param string $data     Data to tokenize
     * @param string $encoding Encoding
     * @return TokenizerInterface
     */
    public function create($data, $encoding = 'UTF-8');
}
