<?php

namespace Gobie\Regex\Tokenizer;

class TokenArray extends TokenNode implements \ArrayAccess, \Countable
{
    public function __construct($data = array())
    {
        $this->data = $data;
        parent::__construct();
    }

    public function offsetExists($offset)
    {
        throw new \LogicException('Method is not implemented');
    }

    public function offsetGet($offset)
    {
        throw new \LogicException('Method is not implemented');
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException('Method is not implemented');
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
            $arr[] = $value instanceof TokenNode ? $value->toArray() : $value;
        }

        return $arr;
    }

    public function count()
    {
        return count($this->data);
    }
}
