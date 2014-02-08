<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

class PcreRegexReplaceTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'replace');

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

    public function provideErrorBehavior()
    {
        $original = parent::provideErrorBehavior();

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

    public function provideSuccessBehavior()
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
            '[] of patterns'                       => array(
                array(array('/[A-Z]/', '/[a-z]/'), array('U', 'u'), self::$subject),
                'Uuuuu Uuuuu'
            ),
            '[] of subjects'                       => array(
                array('/t(\d+)/', 's\\1', array('t1', 'u2', 't3')),
                array('s1', 'u2', 's3')
            ),
            'limit 2'                              => array(
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
            '[] like callback replacement'         => array(
                array(array('/H/', '/W/'), array('PcreRegex', 'test'), self::$subject),
                'PcreRegexello testorld'
            ),
            '[] callback replacement'              => array(
                array(
                    array('/H/', '/W/'),
                    array('\Gobie\Test\Regex\Wrappers\Pcre\PcreRegexReplaceTest', 'dataMethod'),
                    self::$subject
                ),
                'DATAello DATAorld'
            ),
            '[] of patterns with callback'         => array(
                array(
                    array('/[A-Z]/', '/[a-z]/'),
                    function ($matches) {
                        return \ord($matches[0]);
                    },
                    self::$subject
                ),
                '72101108108111 87111114108100'
            ),
            '[] of subjects with callback'         => array(
                array(
                    '/t(\d+)/',
                    function ($matches) {
                        return 's' . $matches[1];
                    },
                    array('t1', 'u2', 't3')
                ),
                array('s1', 'u2', 's3')
            ),
            'limit with callback'                  => array(
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

    public static function dataMethod()
    {
        return 'DATA';
    }
}
