<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexGetAllTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'getAll');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'full match'       => array(
                array('^Hello\sWorld$', self::$subject),
                array(array('Hello World'))
            ),
            'multiple matches' => array(
                array('l', self::$subject),
                array(array('l', 'l', 'l'))
            ),
            '2 subgroups'      => array(
                array('(.)\s(.)', self::$subject),
                array(array('o W'), array('o'), array('W'))
            ),
            'all'              => array(
                array('(.)(\w+)(.)', self::$subject),
                array(
                    array('Hello ', 'World'),
                    array('H', 'W'),
                    array('ello', 'orl'),
                    array(' ', 'd')
                )
            ),
            'no match'         => array(
                array('HelloWorld', self::$subject),
                array()
            ),
            'ignore case'      => array(
                array('L', self::$subject, 'i'),
                array(array('l', 'l', 'l'))
            ),
        );
    }
}
