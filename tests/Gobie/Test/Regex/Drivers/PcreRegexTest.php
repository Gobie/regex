<?php

namespace Gobie\Test\Regex\Drivers;

use Gobie\Regex\Drivers\PcreRegex;
use Gobie\Regex\RegexException;

class PcreRegexTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideTestShouldMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame(PcreRegex::match($pattern, $subject), $expectedResult);
    }

    public function provideTestShouldMatch()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', 'Hello World', array('Hello World'))
        );
    }

    /**
     * @dataProvider provideTestShouldFailWithCompilationError
     */
    public function testShouldFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, '');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Compilation exception should have been thrown');
    }

    public function provideTestShouldFailWithCompilationError()
    {
        return array(
            'incorrect delimiter'   => array(
                'Hello',
                'preg_match(): Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'no ending delimiter'   => array(
                '/Hello',
                'preg_match(): No ending delimiter \'/\' found; pattern: /Hello'
            ),
            'missing )'             => array(
                '/(Hello/',
                'preg_match(): Compilation failed: missing ) at offset 6; pattern: /(Hello/'
            ),
            'unmatched )'             => array(
                '/Hello)/',
                'preg_match(): Compilation failed: unmatched parentheses at offset 5; pattern: /Hello)/'
            ),
            'nothing to repeat'     => array(
                '/+/',
                'preg_match(): Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
            'unsupported \u'        => array(
                "/\uFFFF/",
                'preg_match(): Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid 2 octet utf-8' => array(
                "/\xc3\x28/u",
                "preg_match(): Compilation failed: invalid UTF-8 string at offset 0; pattern: /\xc3\x28/u"
            ),
            'unknown modifier'      => array(
                '//.',
                'preg_match(): Unknown modifier \'.\'; pattern: //.'
            ),
            'empty pattern'         => array(
                '',
                'preg_match(): Empty regular expression; pattern: '
            ),
        );
    }

    /**
     * @dataProvider provideTestShouldFailWithRuntimeError
     */
    public function testShouldFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, $subject);
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Runtime exception should have been thrown');
    }

    public function provideTestShouldFailWithRuntimeError()
    {
        return array(
            'malformed utf-8' => array(
                '//u',
                "\xc3\x28",
                'Malformed UTF-8 data; pattern: //u'
            ),
            'backtrack limit' => array(
                '/(a*)*$/',
                'aaaaaaaaaaaaaaaaaaaaaab',
                'Backtrack limit was exhausted; pattern: /(a*)*$/'
            ),
        );
    }
}
