<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexSplitTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'split');

    public static $subject = 'Hello World';

    public function provideExecuteAndAssert()
    {
        return array(
            'space separated' => array(
                array('\s', self::$subject),
                array('Hello', 'World')
            ),
            'on l'            => array(
                array('l', self::$subject),
                array('He', '', 'o Wor', 'd')
            ),
            'no split'        => array(
                array('\d', self::$subject),
                array('Hello World')
            ),
        );
    }
}
