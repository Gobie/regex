<?php

namespace Gobie\Test\Regex\Wrappers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexGetTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Mb\MbRegex', 'get');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'single match' => array(
                array('l', self::$subject),
                array('l')
            ),
            '2 subgroups'  => array(
                array('(Hello)\s(World)', self::$subject),
                array('Hello World', 'Hello', 'World')
            ),
            'no match'     => array(
                array('HelloWorld', self::$subject),
                array()
            ),
            'ignore case'  => array(
                array('L', self::$subject, 'i'),
                array('l')
            ),
        );
    }
}
