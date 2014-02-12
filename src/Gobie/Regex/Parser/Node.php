<?php

namespace Gobie\Regex\Parser;

/**
 * @property array $modifiers Array of modifiers
 * @property array $delimiters Array of delimiters
 * @property NodeArray $stack Stack
 */
class Node
{

    public function __construct($data = array())
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function toArray()
    {
        $arr = array();
        foreach ($this as $name => $value) {
            $arr[$name] = $value instanceof Node ? $value->toArray() : $value;
        }

        return $arr;
    }
}
