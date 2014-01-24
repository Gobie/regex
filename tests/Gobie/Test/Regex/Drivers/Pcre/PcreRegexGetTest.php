<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Test\Regex\Pcre\PcreRegexBaseTest;

class PcreRegexGetTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'get');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'full match'               => array(
                array('/^Hello\sWorld$/', self::$subject, 0, 0),
                array('Hello World')
            ),
            'single match'             => array(
                array('/l/', self::$subject, 0, 0),
                array('l')
            ),
            '2 subgroups'              => array(
                array('/(Hello)\s(World)/', self::$subject, 0, 0),
                array('Hello World', 'Hello', 'World')
            ),
            'no match'                 => array(
                array('/HelloWorld/', self::$subject, 0, 0),
                array()
            ),
            'uppercase after offset 1' => array(
                array('/[A-Z]/', self::$subject, 0, 1),
                array('W')
            ),
            'offset capture'           => array(
                array('/[A-Z]/', self::$subject, \PREG_OFFSET_CAPTURE, 0),
                array(array('H', 0))
            ),
        );
    }
}
