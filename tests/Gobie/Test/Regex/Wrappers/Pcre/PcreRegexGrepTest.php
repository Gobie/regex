<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexGrepTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'grep');

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Change subject from string to array
        $args[1] = (array) $args[1];
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideSuccessBehavior()
    {
        return array(
            'space separated'        => array(
                array('/\s/', array('a b', 'bc', 'c d')),
                array('a b', 2 => 'c d')
            ),
            'none'                   => array(
                array('/\d/', array('a b', 'bc', 'c d')),
                array()
            ),
            'space separated invert' => array(
                array('/\s/', array('a b', 'bc', 'c d'), \PREG_GREP_INVERT),
                array(1 => 'bc')
            ),
            'string subject'         => array(
                array('/\s/', 'a b'),
                array('a b')
            ),
        );
    }
}
