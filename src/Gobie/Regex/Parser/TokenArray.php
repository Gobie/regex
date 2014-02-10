<?php

namespace Gobie\Regex\Parser;

class TokenArray extends Token implements \ArrayAccess, \Countable
{
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!isset($this->data[$offset])) {
            throw new \InvalidArgumentException('Accessing undefined offset "' . $offset . '"');
        }

        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function count()
    {
        return count($this->data);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function last()
    {
        return $this->data[count($this->data) - 1];
    }

    public function pop()
    {
        return array_pop($this->data);
    }
    public function toArray()
    {
        $arr = array();
        foreach ($this->data as $value) {
            $arr[] = $value instanceof Token ? $value->toArray() : $value;
        }

        return $arr;
    }
}
