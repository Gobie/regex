<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

class PcreRegexReplaceCallbackTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'replaceCallback');

    public static $subject = 'Hello World';

    /**
     * @expectedException \PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Undefined variable: undef
     * @dataProvider             provideReplaceCallbackNotice
     */
    public function testShouldReplaceCallbackAndThrowNotice($pattern, $callback, $subject)
    {
        \call_user_func_array(self::$method, array($pattern, $callback, $subject));
    }

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add callback as second argument between pattern and subject
        \array_splice($args, 1, 0, array(
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
                    '/^Hello\sWorld$/',
                    function () {
                        return 'Good day';
                    },
                    self::$subject,
                    -1
                ),
                'Good day'
            ),
            'lowercase to uppercase' => array(
                array(
                    '/[a-z]/',
                    function ($matches) {
                        return \strtoupper($matches[0]);
                    },
                    self::$subject,
                    -1
                ),
                'HELLO WORLD'
            ),
            'full replace by groups' => array(
                array(
                    '/^(\w+)\s(\w+)$/',
                    function ($matches) {
                        return $matches[1] . '-' . $matches[2];
                    },
                    self::$subject,
                    -1
                ),
                'Hello-World'
            ),
            'replace each char'      => array(
                array(
                    '/./',
                    function ($matches) {
                        return \ord($matches[0]);
                    },
                    self::$subject,
                    -1
                ),
                '721011081081113287111114108100'
            ),
            'no match'               => array(
                array(
                    '/HelloWorld/',
                    function () {
                        return '';
                    },
                    self::$subject,
                    -1
                ),
                'Hello World'
            ),
            'array of patterns'      => array(
                array(
                    array('/[A-Z]/', '/[a-z]/'),
                    function ($matches) {
                        return \ord($matches[0]);
                    },
                    self::$subject,
                    -1
                ),
                '72101108108111 87111114108100'
            ),
            'array of subjects'      => array(
                array(
                    '/t(\d+)/',
                    function ($matches) {
                        return 's' . $matches[1];
                    },
                    array('t1', 'u2', 't3'),
                    -1
                ),
                array('s1', 'u2', 's3')
            ),
            'use limit'              => array(
                array(
                    '/l/',
                    function ($matches) {
                        return '*';
                    },
                    self::$subject,
                    2
                ),
                'He**o World'
            ),
        );
    }

    public function provideReplaceCallbackNotice()
    {
        return array(
            'notice' => array(
                '/./',
                function () {
                    ++$undef;

                    return '';
                },
                self::$subject
            ),
        );
    }
}
