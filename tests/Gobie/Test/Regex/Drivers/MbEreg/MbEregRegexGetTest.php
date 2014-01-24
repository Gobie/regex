<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

/**
 * @requires extension mbstring
 */
class MbEregRegexGetTest extends MbEregRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\MbEreg\MbEregRegex', 'get');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'simple hello world' => array(
                array('Hello\sWorld', self::$subject),
                array('Hello World')
            ),
            'single match'       => array(
                array('l', self::$subject),
                array('l')
            ),
            '2 subgroups'        => array(
                array('(Hello)\s(World)', self::$subject),
                array('Hello World', 'Hello', 'World')
            ),
            'no match'           => array(
                array('HelloWorld', self::$subject),
                array()
            ),
        );
    }
}
