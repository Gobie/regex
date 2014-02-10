<?php

namespace Gobie\Regex\Parser;

class NodeFactory implements NodeFactoryInterface
{

    public function createChar($char)
    {
        return new Node(array(
            'type'  => 'char',
            'value' => $char
        ));
    }

    public function createPosition($char)
    {
        return new Node(array(
            'type'  => 'position',
            'value' => $char
        ));
    }

    public function createSet($not = false, $tokens = array())
    {
        return new Node(array(
            'type' => 'set',
            'not'  => $not,
            'set'  => $this->createTokenArray($tokens)
        ));
    }

    public function createGroup($remember = true)
    {
        return new Node(array(
            'type'     => 'group',
            'remember' => $remember,
            'stack'    => $this->createTokenArray(),
        ));
    }

    public function createRoot()
    {
        return new Node(array(
            'type'       => 'root',
            'delimiters' => array(),
            'modifiers'  => array(),
            'stack'      => $this->createTokenArray()
        ));
    }

    public function createDot()
    {
        return $this->createSet(true, array(
            $this->createChar("\n")
        ));
    }

    public function createRepetition($from, $to)
    {
        return new Node(array(
            'type'  => 'repetition',
            'from'  => $from,
            'to'    => $to
        ));
    }

    public function createTokenArray($tokens = array())
    {
        return new NodeArray($tokens);
    }
}
