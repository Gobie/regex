<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

class PcreRegexSplitTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'split');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'space separated'   => array(
                array('/\s/', self::$subject, -1, 0),
                array('Hello', 'World')
            ),
            'on characters'     => array(
                array('/(?<!^)(?!$)/', self::$subject, -1, 0),
                array('H', 'e', 'l', 'l', 'o', ' ', 'W', 'o', 'r', 'l', 'd')
            ),
            'no split'          => array(
                array('/\d/', self::$subject, -1, 0),
                array('Hello World')
            ),
            'use limit'         => array(
                array('/\s/', self::$subject, 1, 0),
                array('Hello World')
            ),
            'no empty'          => array(
                array('//', self::$subject, -1, \PREG_SPLIT_NO_EMPTY),
                array('H', 'e', 'l', 'l', 'o', ' ', 'W', 'o', 'r', 'l', 'd')
            ),
            'delimiter capture' => array(
                array('/(\s)/', self::$subject, -1, \PREG_SPLIT_DELIM_CAPTURE),
                array('Hello', ' ', 'World')
            ),
            'offset capture'    => array(
                array('/(\s)/', self::$subject, -1, \PREG_SPLIT_OFFSET_CAPTURE),
                array(array('Hello', 0), array('World', 6))
            ),
        );
    }
}
