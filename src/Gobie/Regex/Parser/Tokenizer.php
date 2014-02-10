<?php

namespace Gobie\Regex\Parser;

use Gobie\Regex\Wrappers\Pcre\PcreRegex;

class Tokenizer implements TokenizerInterface
{

    private $encoding;

    private $data;

    private $position;

    private $length;

    public function __construct($data, $encoding = 'UTF-8')
    {
        if (!is_string($data)) {
            throw new \InvalidArgumentException('Argument must be a string, but "' . gettype($data) . '" was given');
        }

        $this->encoding = $encoding;
        $this->data     = $data;
        $this->length   = \mb_strlen($data, $encoding);
        $this->position = 0;
    }

    public function current()
    {
        return \mb_substr($this->data, $this->position, 1, $this->encoding);
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position < $this->length;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function pop($regex)
    {
        $data = PcreRegex::get($regex, \mb_substr($this->data, $this->position + 1, null, $this->encoding));
        if ($data) {
            $this->position += \mb_strlen($data[0], $this->encoding);
        }

        return $data;
    }
}
