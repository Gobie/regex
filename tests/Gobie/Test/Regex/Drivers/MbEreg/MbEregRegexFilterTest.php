<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

/**
 * @requires extension mbstring
 */
class MbEregRegexFilterTest extends MbEregRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\MbEreg\MbEregRegex', 'filter');

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add replacement as second argument between pattern and subject
        array_splice($args, 1, 0, array(''));
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
        );
    }
}
