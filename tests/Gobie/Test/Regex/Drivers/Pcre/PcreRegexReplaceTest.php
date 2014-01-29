<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

class PcreRegexReplaceTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'replace');

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

    public function provideCompilationError()
    {
        $original = parent::provideCompilationError();

        $specificData = array(
            'string pattern and array replacement' => array(
                array(
                    '/[A-Z]/',
                    array(),
                    ''
                ),
                'Parameter mismatch, pattern is a string while replacement is an array; pattern: /[A-Z]/'
            ),
            'incorrect patterns in array'          => array(
                array(
                    array('/[A-Z]/', '*', '/[a-z]/', '+'),
                    '',
                    ''
                ),
                'No ending delimiter \'*\' found; pattern: /[A-Z]/, *, /[a-z]/, +'
            ),
        );

        return $this->addCallback($original) + $specificData;
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'no match'                             => array(
                array('/HelloWorld/', '', self::$subject),
                'Hello World'
            ),
            'full replace'                         => array(
                array('/^Hello\sWorld$/', 'Good day', self::$subject),
                'Good day'
            ),
            'multiple replaces'                    => array(
                array('/l/', '*', self::$subject),
                'He**o Wor*d'
            ),
            'array of patterns'                    => array(
                array(array('/[A-Z]/', '/[a-z]/'), array('U', 'u'), self::$subject),
                'Uuuuu Uuuuu'
            ),
            'array of subjects'                    => array(
                array('/t(\d+)/', 's\\1', array('t1', 'u2', 't3')),
                array('s1', 'u2', 's3')
            ),
            'use limit'                            => array(
                array('/l/', '*', self::$subject, 2),
                'He**o World'
            ),
            'empty patterns'                       => array(
                array(array(), array(), self::$subject),
                self::$subject
            ),
            'mixed replacements'                   => array(
                array(
                    array('/[A-Z]/', '/[a-z]/'),
                    array('a', '-'),
                    array(self::$subject, \strrev(self::$subject))
                ),
                array('----- -----', '----- -----')
            ),
            'string callback replacement'          => array(
                array('/l/', 'strlen', self::$subject),
                'Hestrlenstrleno Worstrlend'
            ),
            'array like callback replacement'          => array(
                array(array('/H/', '/W/'), array('PcreRegex', 'test'), self::$subject),
                'PcreRegexello testorld'
            ),
            'full replace by groups with callback' => array(
                array(
                    '/^(\w+)\s(\w+)$/',
                    function ($matches) {
                        return $matches[1] . '-' . $matches[2];
                    },
                    self::$subject
                ),
                'Hello-World'
            ),
            'no match with callback'               => array(
                array(
                    '/HelloWorld/',
                    function () {
                        return '';
                    },
                    self::$subject
                ),
                'Hello World'
            ),
            'array of patterns with callback'      => array(
                array(
                    array('/[A-Z]/', '/[a-z]/'),
                    function ($matches) {
                        return \ord($matches[0]);
                    },
                    self::$subject
                ),
                '72101108108111 87111114108100'
            ),
            'array of subjects with callback'      => array(
                array(
                    '/t(\d+)/',
                    function ($matches) {
                        return 's' . $matches[1];
                    },
                    array('t1', 'u2', 't3')
                ),
                array('s1', 'u2', 's3')
            ),
            'use limit with callback'              => array(
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
