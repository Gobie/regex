<?php

namespace Gobie\Regex\Parser;

interface TokenizerInterface extends \Iterator
{
    public function pop($regex);
}
