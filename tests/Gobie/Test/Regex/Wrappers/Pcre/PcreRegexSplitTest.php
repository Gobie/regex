<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexSplitTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'split');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'space separated'   => array(
                array('/\s/', self::$subject),
                array('Hello', 'World')
            ),
            'no split'          => array(
                array('/\d/', self::$subject),
                array('Hello World')
            ),
            'limit 1'         => array(
                array('/\s/', self::$subject, 1),
                array('Hello World')
            ),
            'split no empty'          => array(
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
