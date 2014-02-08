<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexGetTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'get');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'full match'               => array(
                array('/^Hello\sWorld$/', self::$subject),
                array('Hello World')
            ),
            'single match'             => array(
                array('/l/', self::$subject),
                array('l')
            ),
            '2 subgroups'              => array(
                array('/(Hello)\s(World)/', self::$subject),
                array('Hello World', 'Hello', 'World')
            ),
            'no match'                 => array(
                array('/HelloWorld/', self::$subject),
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
