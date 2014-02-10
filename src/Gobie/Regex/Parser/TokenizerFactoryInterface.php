<?php

namespace Gobie\Regex\Parser;

interface TokenizerFactoryInterface
{
    public function create($data, $encoding = 'UTF-8');
}
