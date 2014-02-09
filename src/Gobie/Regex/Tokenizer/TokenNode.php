<?php

namespace Gobie\Regex\Tokenizer;

/**
 * @property array $modifiers Array of modifiers
 * @property array $delimiters Array of delimiters
 * @property TokenArray $stack Stack
 */
class TokenNode
{

    public function __construct($options = array())
    {
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }

    public function toArray()
    {
        $arr = array();
        foreach ($this as $name => $value) {
            $arr[$name] = $value instanceof TokenNode ? $value->toArray() : $value;
        }

        return $arr;
    }
}
