<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexGetAllTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'getAll');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'multiple matches'          => array(
                array('/l/', self::$subject),
                array(array('l', 'l', 'l'))
            ),
            'all'                       => array(
                array('/(.)(\w+)(.)/', self::$subject),
                array(
                    array('Hello ', 'World'),
                    array('H', 'W'),
                    array('ello', 'orl'),
                    array(' ', 'd')
                )
            ),
            'no match'                  => array(
                array('/HelloWorld/', self::$subject),
                array()
            ),
            'uppercase after offset 1'  => array(
                array('/[A-Z]/', self::$subject, 0, 1),
                array(array('W'))
            ),
            '2 subpatterns'             => array(
                array('/([A-Z])(.)/', self::$subject),
                array(array('He', 'Wo'), array('H', 'W'), array('e', 'o'))
            ),
            '2 subpatterns; set order'  => array(
                array('/([A-Z])(.)/', self::$subject, \PREG_SET_ORDER, 0),
                array(array('He', 'H', 'e'), array('Wo', 'W', 'o'))
            ),
            'offset capture'            => array(
                array('/[A-Z]/', self::$subject, \PREG_OFFSET_CAPTURE, 0),
                array(array(array('H', 0), array('W', 6)))
            ),
            'offset capture; set order' => array(
                array('/[A-Z]/', self::$subject, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER, 0),
                array(array(array('H', 0)), array(array('W', 6)))
            ),
        );
    }
}
