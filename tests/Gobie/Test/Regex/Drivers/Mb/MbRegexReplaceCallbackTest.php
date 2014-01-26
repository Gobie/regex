<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 * @requires function mb_ereg_replace_callback
 */
class MbRegexReplaceCallbackTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'replaceCallback');

    public static $subject = 'Hello World';

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add callback as second argument between pattern and subject
        array_splice($args, 1, 0, array(
            function () {
            }
        ));
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'full replace'           => array(
                array(
                    '^Hello\sWorld$',
                    function () {
                        return 'Good day';
                    },
                    self::$subject
                ),
                'Good day'
            ),
            'lowercase to uppercase' => array(
                array(
                    '[a-z]',
                    function ($matches) {
                        return \strtoupper($matches[0]);
                    },
                    self::$subject
                ),
                'HELLO WORLD'
            ),
            'full replace by groups' => array(
                array(
                    '^(\w+)\s(\w+)$',
                    function ($matches) {
                        return $matches[1] . '-' . $matches[2];
                    },
                    self::$subject
                ),
                'Hello-World'
            ),
            'replace each char'      => array(
                array(
                    '.',
                    function ($matches) {
                        return ord($matches[0]);
                    },
                    self::$subject
                ),
                '721011081081113287111114108100'
            ),
            'no match'               => array(
                array(
                    'HelloWorld',
                    function () {
                        return '';
                    },
                    self::$subject
                ),
                'Hello World'
            ),
        );
    }
}
