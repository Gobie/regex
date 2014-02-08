<?php

namespace Gobie\Test\Regex\Wrappers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexSplitTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Mb\MbRegex', 'split');

    public static $subject = 'Hello World';

    public function provideSuccessBehavior()
    {
        return array(
            'on l'                  => array(
                array('l', self::$subject),
                array('He', '', 'o Wor', 'd')
            ),
            'on l limit 3'          => array(
                array('l', self::$subject, '', 3),
                array('He', '', 'o World')
            ),
            'no split'              => array(
                array('\d', self::$subject),
                array('Hello World')
            ),
            'ignore case'           => array(
                array('L', self::$subject, 'i'),
                array('He', '', 'o Wor', 'd')
            ),
            'pattern hitting edges' => array(
                array('(.)(\w+).', self::$subject),
                array('', '', '')
            ),
        );
    }
}
