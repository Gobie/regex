<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Test\Regex\Pcre\PcreRegexBaseTest;

class PcreRegexMatchTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'match');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'full match'           => array(
                array('/^Hello\sWorld$/', self::$subject, 0),
                true
            ),
            'single match'         => array(
                array('/l/', self::$subject, 0),
                true
            ),
            '2 subgroups'          => array(
                array('/(Hello)\s(World)/', self::$subject, 0),
                true
            ),
            'no match'             => array(
                array('/HelloWorld/', self::$subject, 0),
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
            'e not after H'        => array(
                array('/(?<!H)e/', self::$subject, 1),
                false
            ),
        );
    }
}
