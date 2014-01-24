<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

/**
 * @requires extension mbstring
 */
class MbEregRegexGrepTest extends MbEregRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\MbEreg\MbEregRegex', 'grep');

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
        );
    }
}