<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexFilterTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'filter');

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add replacement as second argument between pattern and subject
        \array_splice($args, 1, 0, array(''));
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'all'             => array(
                array('.', '-', array('a', 'b', 'c')),
                array('-', '-', '-')
            ),
            'space separated' => array(
                array('\s', '-', array('a b', 'bc', 'c d')),
                array('a-b', 2 => 'c-d')
            ),
            'none'            => array(
                array('\d', '', array('a b', 'bc', 'c d')),
                array()
            ),
            'string subject' => array(
                array('B', 'X', 'a b', 'i'),
                array('a X')
            ),
            'ignore case' => array(
                array('B', 'X', array('a b', 'bc', 'c d'), 'i'),
                array('a X', 'Xc')
            ),
        );
    }
}
