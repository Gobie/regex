<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexGrepTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'grep');

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Change subject from string to array
        $args[1] = (array) $args[1];
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'all'             => array(
                array('.', array('a', 'b', 'c')),
                array('a', 'b', 'c')
            ),
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
