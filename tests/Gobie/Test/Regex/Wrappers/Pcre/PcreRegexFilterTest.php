<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

/**
 * @requires function preg_filter
 */
class PcreRegexFilterTest extends PcreRegexBaseTest
{

    public static $method = array('\Gobie\Regex\Wrappers\Pcre\PcreRegex', 'filter');

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

        $withString = \array_map(function ($item) {
            \array_splice($item[0], 1, 0, array(''));

            return $item;
        }, $original);

        return $withString + $specificData;
    }

    public function provideSuccessBehavior()
    {
        return array(
            'space separated'                     => array(
                array('/\s/', '-', array('a b', 'bc', 'c d')),
                array('a-b', 2 => 'c-d')
            ),
            'none'                                => array(
                array('/\d/', '', array('a b', 'bc', 'c d')),
                array()
            ),
            'space separated and replace limit 1' => array(
                array('/\s/', '-', array('a b', 'bc', 'c d e'), 1),
                array('a-b', 2 => 'c-d e')
            ),
        );
    }
}
