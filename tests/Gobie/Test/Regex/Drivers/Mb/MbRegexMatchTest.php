<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexMatchTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'match');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            '2 subgroups' => array(
                array('(Hello)\s(World)', self::$subject),
                true
            ),
            'no match'    => array(
                array('HelloWorld', self::$subject),
                false
            ),
            'ignore case' => array(
                array('L', self::$subject, 'i'),
                true
            ),
        );
    }
}
