<?php

namespace Gobie\Regex\Tokenizer;

interface TokenFactoryInterface
{

    public function createCharToken($char);
}
