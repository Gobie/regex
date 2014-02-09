<?php

namespace Gobie\Regex\Tokenizer;

class ParserException extends \RuntimeException
{
    public function __construct($message, $position = null)
    {
        if ($position !== null) {
            $message .= ' at position ' . $position;
        }

        parent::__construct($message, 0, null);
    }

}
