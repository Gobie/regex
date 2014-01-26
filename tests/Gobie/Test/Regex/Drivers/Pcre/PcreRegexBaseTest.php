<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Test\Regex\RegexBaseTest;

abstract class PcreRegexBaseTest extends RegexBaseTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        ini_set('pcre.backtrack_limit', 100);
    }

    public function provideCompilationError()
    {
        return array(
            'incorrect delimiter'   => array(
                array('Hello', ''),
                'Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'no ending delimiter'   => array(
                array('/Hello', ''),
                'No ending delimiter \'/\' found; pattern: /Hello'
            ),
            'missing ]'             => array(
                array('/[a-z/', ''),
                'Compilation failed: missing terminating ] for character class at offset 4; pattern: /[a-z/'
            ),
            'missing )'             => array(
                array('/(Hello/', ''),
                'Compilation failed: missing ) at offset 6; pattern: /(Hello/'
            ),
            'unmatched )'           => array(
                array('/Hello)/', ''),
                'Compilation failed: unmatched parentheses at offset 5; pattern: /Hello)/'
            ),
            'nothing to repeat'     => array(
                array('/+/', ''),
                'Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
            'unsupported \u'        => array(
                array("/\uFFFF/", ''),
                'Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid 2 octet utf-8' => array(
                array("/\xc3\x28/u", ''),
                "Compilation failed: invalid UTF-8 string at offset 0; pattern: /\xc3\x28/u"
            ),
            'unknown modifier'      => array(
                array('//.', ''),
                'Unknown modifier \'.\'; pattern: //.'
            ),
            'empty pattern'         => array(
                array('', ''),
                'Empty regular expression; pattern: '
            ),
            'malformed utf-8'       => array(
                array('//u', "\xc3\x28"),
                'Malformed UTF-8 data; pattern: //u'
            ),
            'backtrack limit'       => array(
                array('/(a*)*$/', 'aaaaaab'),
                'Backtrack limit was exhausted; pattern: /(a*)*$/'
            ),
        );
    }
}
