<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexMatchTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'match');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'full match'           => array(
                array('/^Hello\sWorld$/', self::$subject),
                true
            ),
            'single match'         => array(
                array('/l/', self::$subject),
                true
            ),
            '2 subgroups'          => array(
                array('/(Hello)\s(World)/', self::$subject),
                true
            ),
            'no match'             => array(
                array('/HelloWorld/', self::$subject),
                false
            ),
            'e at offset 1'        => array(
                array('/e/', self::$subject, 1),
                true
            ),
            'e not after offset 2' => array(
                array('/e/', self::$subject, 2),
                false
            ),
        );
    }
}
