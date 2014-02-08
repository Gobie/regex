<?php

namespace Gobie\Test\Regex\Wrappers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexGrepTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Mb\MbRegex', 'grep');

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Change subject from string to array
        $args[1] = (array) $args[1];
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideSuccessBehavior()
    {
        return array(
            'space separated' => array(
                array('\s', array('a b', 'bc', 'c d')),
                array('a b', 2 => 'c d')
            ),
            'none'            => array(
                array('\d', array('a b', 'bc', 'c d')),
                array()
            ),
            'string subject'  => array(
                array('b', 'a b'),
                array('a b')
            ),
            'ignore case'     => array(
                array('B', array('a b', 'bc', 'c d'), 'i'),
                array('a b', 'bc')
            ),
        );
    }
}
