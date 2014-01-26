<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexMatchTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'match');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'simple hello world' => array(
                array('Hello\sWorld', self::$subject),
                true
            ),
            'single match'       => array(
                array('l', self::$subject),
                true
            ),
            '2 subgroups'        => array(
                array('(Hello)\s(World)', self::$subject),
                true
            ),
            'no match'           => array(
                array('HelloWorld', self::$subject),
                false
            ),
        );
    }
}
