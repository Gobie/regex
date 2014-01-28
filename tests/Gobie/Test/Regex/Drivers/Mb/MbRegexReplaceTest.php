<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 * @requires function mb_ereg_replace_callback
 */
class MbRegexReplaceTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'replace');

    public static $subject = 'Hello World';

    public function provideCompilationError()
    {
        $original = parent::provideCompilationError();

        $specificData = array(
            'string pattern and array replacement' => array(
                array(
                    '[A-Z]',
                    array(),
                    ''
                ),
                'Parameter mismatch, pattern is a string while replacement is an array; pattern: [A-Z]'
            ),
            'incorrect patterns in array'          => array(
                array(
                    array('[A-Z]', '*', '[a-z]', '+'),
                    '',
                    ''
                ),
                'mbregex compile err: target of repeat operator is not specified; pattern: [A-Z], *, [a-z], +'
            ),
        );

        return $this->addCallback($original) + $specificData;
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'full replace'                                => array(
                array('Hello\sWorld', 'Good day', self::$subject),
                'Good day'
            ),
            'empty pattern'                               => array(
                array('', '-', self::$subject),
                '-H-e-l-l-o- -W-o-r-l-d-'
            ),
            'no match'                                    => array(
                array('HelloWorld', '', self::$subject),
                'Hello World'
            ),
            'ignore case'                                 => array(
                array('L', '*', self::$subject, 'i'),
                'He**o Wor*d'
            ),
            '[] of patterns & replacements'               => array(
                array(array('[A-Z]', '[a-z]'), array('U', 'u'), self::$subject),
                'Uuuuu Uuuuu'
            ),
            '[] of patterns & shorter [] of replacements' => array(
                array(array('[A-Z]', '[a-z]'), array('U'), self::$subject),
                'U U'
            ),
            '[] of patterns & string replacement'         => array(
                array(array('[A-Z]', '[a-z]'), 'U', self::$subject),
                'UUUUU UUUUU'
            ),
            '[] of patterns & replacements & subjects'    => array(
                array(array('[A-Z]', '[a-z]'), array('U', 'u'), array(self::$subject, \strrev(self::$subject))),
                array('Uuuuu Uuuuu', 'uuuuU uuuuU')
            ),
            'empty [] of patterns'                        => array(
                array(array(), array(), self::$subject),
                self::$subject
            ),
            'full replace by groups with callback'        => array(
                array(
                    '^(\w+)\s(\w+)$',
                    function ($matches) {
                        return $matches[1] . '-' . $matches[2];
                    },
                    self::$subject
                ),
                'Hello-World'
            ),
            'no match with callback'                      => array(
                array(
                    'HelloWorld',
                    function () {
                        return '';
                    },
                    self::$subject
                ),
                'Hello World'
            ),
            'ignore case with callback'                   => array(
                array(
                    '[a-z]',
                    function ($matches) {
                        return \strtolower($matches[0]);
                    },
                    self::$subject,
                    'i'
                ),
                'hello world'
            ),
            'mixed replacements'                          => array(
                array(
                    array('[A-Z]', '[a-z]'),
                    array(
                        function ($matches) {
                            return \strtolower($matches[0]);
                        },
                        '-'
                    ),
                    array(self::$subject, \strrev(self::$subject))
                ),
                array('----- -----', '----- -----')
            ),
        );
    }
}
