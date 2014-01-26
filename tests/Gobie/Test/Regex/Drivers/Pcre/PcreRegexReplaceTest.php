<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

class PcreRegexReplaceTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Pcre\PcreRegex', 'replace');

    public static $subject = 'Hello World';

    /**
     * @dataProvider provideReplaceError
     */
    public function testShouldReplaceAndFail($args, $exceptionMessage)
    {
        parent::executeAndFail(self::$method, $args, $exceptionMessage);
    }

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        // Add replacement as second argument between pattern and subject
        array_splice($args, 1, 0, array(''));
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'no match'          => array(
                array('/HelloWorld/', '', self::$subject, -1),
                'Hello World'
            ),
            'full replace'      => array(
                array('/^Hello\sWorld$/', 'Good day', self::$subject, -1),
                'Good day'
            ),
            'multiple replaces' => array(
                array('/l/', '*', self::$subject, -1),
                'He**o Wor*d'
            ),
            'array of patterns' => array(
                array(array('/[A-Z]/', '/[a-z]/'), array('U', 'u'), self::$subject, -1),
                'Uuuuu Uuuuu'
            ),
            'array of subjects' => array(
                array('/t(\d+)/', 's\\1', array('t1', 'u2', 't3'), -1),
                array('s1', 'u2', 's3')
            ),
            'use limit'         => array(
                array('/l/', '*', self::$subject, 2),
                'He**o World'
            ),
        );
    }

    public function provideReplaceError()
    {
        return array(
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
    }
}
