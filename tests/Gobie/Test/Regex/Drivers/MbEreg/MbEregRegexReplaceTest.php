<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

/**
 * @requires extension mbstring
 */
class MbEregRegexReplaceTest extends MbEregRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\MbEreg\MbEregRegex', 'replace');

    public static $subject = 'Hello World';

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add replacement as second argument between pattern and subject
        array_splice($args, 1, 0, array(''));
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'simple hello world' => array(
                array('Hello\sWorld', 'Good day', self::$subject),
                'Good day'
            ),
            'multiple matches'   => array(
                array('l', '*', self::$subject),
                'He**o Wor*d'
            ),
            '2 matches'          => array(
                array('[A-Z]', '$', self::$subject),
                '$ello $orld'
            ),
            'empty pattern'      => array(
                array('', '-', self::$subject),
                '-H-e-l-l-o- -W-o-r-l-d-'
            ),
            'no match'           => array(
                array('HelloWorld', '', self::$subject),
                'Hello World'
            ),
        );
    }
}
