<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

use Gobie\Test\Regex\Wrappers\RegexBaseTest;

abstract class PcreRegexBaseTest extends RegexBaseTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        \ini_set('pcre.backtrack_limit', 100);
    }

    public function provideErrorBehavior()
    {
        return array(
            'incorrect delimiter'   => array(
                array('Hello', ''),
                'Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'unsupported \u'        => array(
                array("/\uFFFF/", ''),
                'Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid utf-8 pattern' => array(
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
            'invalid utf-8 subject' => array(
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
