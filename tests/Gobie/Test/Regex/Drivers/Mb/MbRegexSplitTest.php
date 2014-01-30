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
            'on l'        => array(
                array('l', self::$subject),
                array('He', '', 'o Wor', 'd')
            ),
            'no split'    => array(
                array('\d', self::$subject),
                array('Hello World')
            ),
            'limit 3'     => array(
                array('l', self::$subject, '', 3),
                array('He', '', 'o World')
            ),
            'ignore case' => array(
                array('L', self::$subject, 'i'),
                array('He', '', 'o Wor', 'd')
            ),
            'multiple nothing' => array(
                array('(.)(\w+).', self::$subject),
                array('', '', '')
            ),
        );
    }
}
