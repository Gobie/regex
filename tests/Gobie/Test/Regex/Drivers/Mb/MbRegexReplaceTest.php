<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 */
class MbRegexReplaceTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'replace');

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
        \array_splice($args, 1, 0, array(''));
        parent::executeAndFail($method, $args, $exceptionMessage);
    }

    public function provideExecuteAndAssert()
    {
        return array(
            'full replace'                                => array(
                array('Hello\sWorld', 'Good day', self::$subject),
                'Good day'
            ),
            'multiple matches'                            => array(
                array('l', '*', self::$subject),
                'He**o Wor*d'
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
            '[] of subjects'                              => array(
                array('[A-Z]', 'U', array(self::$subject, \strrev(self::$subject))),
                array('Uello Uorld', 'dlroU olleU')
            ),
            '[] of patterns & replacements & subjects'    => array(
                array(array('[A-Z]', '[a-z]'), array('U', 'u'), array(self::$subject, \strrev(self::$subject))),
                array('Uuuuu Uuuuu', 'uuuuU uuuuU')
            ),
            'empty [] of patterns'                        => array(
                array(array(), array(), self::$subject),
                self::$subject
            ),
        );
    }

    public function provideReplaceError()
    {
        return array(
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
    }
}
