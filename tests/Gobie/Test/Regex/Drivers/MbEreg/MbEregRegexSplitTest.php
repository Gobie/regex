<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

/**
 * @requires extension mbstring
 */
class MbEregRegexSplitTest extends MbEregRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\MbEreg\MbEregRegex', 'split');

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
