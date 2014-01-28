<?php

namespace Gobie\Test\Regex\Drivers\Mb;

/**
 * @requires extension mbstring
 * @requires function mb_ereg_replace_callback
 */
class MbRegexFilterTest extends MbRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Drivers\Mb\MbRegex', 'filter');

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
            'all'                              => array(
                array('.', '-', array('a', 'b', 'c')),
                array('-', '-', '-')
            ),
            'space separated'                  => array(
                array('\s', '-', array('a b', 'bc', 'c d')),
                array('a-b', 2 => 'c-d')
            ),
            'none'                             => array(
                array('\d', '', array('a b', 'bc', 'c d')),
                array()
            ),
            'string subject'                   => array(
                array('B', 'X', 'a b', 'i'),
                array('a X')
            ),
            'ignore case'                      => array(
                array('B', 'X', array('a b', 'bc', 'c d'), 'i'),
                array('a X', 'Xc')
            ),
            '[] pattern & replacements '       => array(
                array(array('a', 'b'), array('x', 'y'), array('a b', 'b c', 'c d')),
                array('x y', 'y c')
            ),
            '[] pattern & string replacements' => array(
                array(array('c'), 'z', array('a b', 'b c', 'c d')),
                array(1 => 'b z', 2 => 'z d')
            ),
            'empty pattern & [] subjects'      => array(
                array(array(), array(), array('a b', 'b c', 'c d')),
                array()
            ),
            'replacement with callback'        => array(
                array(
                    'b',
                    function () {
                        return 'X';
                    },
                    'a b'
                ),
                array('a X')
            ),
        );
    }
}
