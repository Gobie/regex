<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Regex\Drivers\Pcre\PcreRegex;
use Gobie\Regex\RegexException;

class PcreRegexTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame(PcreRegex::match($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider providePregMatchCompilationError
     */
    public function testShouldTryToMatchButFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, '');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Compilation exception should have been thrown');
    }

    /**
     * @dataProvider provideFailWithRuntimeError
     */
    public function testShouldTryToMatchFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, $subject);
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Runtime exception should have been thrown');
    }

    /**
     * @dataProvider provideGet
     */
    public function testShouldGet($pattern, $subject, $expectedResult)
    {
        $this->assertSame(PcreRegex::get($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider providePregMatchCompilationError
     */
    public function testShouldTryToGetButFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::get($pattern, '');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Compilation exception should have been thrown');
    }

    /**
     * @dataProvider provideFailWithRuntimeError
     */
    public function testShouldTryToGetFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::get($pattern, $subject);
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Runtime exception should have been thrown');
    }

    /**
     * @dataProvider provideGetAll
     */
    public function testShouldGetAll($pattern, $subject, $expectedResult)
    {
        $this->assertSame(PcreRegex::getAll($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider providePregMatchAllCompilationError
     */
    public function testShouldTryToGetAllButFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::getAll($pattern, '');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Compilation exception should have been thrown');
    }

    /**
     * @dataProvider provideFailWithRuntimeError
     */
    public function testShouldTryToGetAllFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::getAll($pattern, $subject);
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Runtime exception should have been thrown');
    }

    public function provideMatch()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', 'Hello World', true),
            '2 subgroups'        => array('/(Hello)\s(World)/', 'Hello World', true),
            'no match'           => array('/HelloWorld/', 'Hello World', false),
        );
    }

    public function provideGet()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', 'Hello World', array('Hello World')),
            '2 subgroups'        => array('/(Hello)\s(World)/', 'Hello World', array('Hello World', 'Hello', 'World')),
            'no match'           => array('/HelloWorld/', 'Hello World', array()),
        );
    }

    public function provideGetAll()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', 'Hello World', array(array('Hello World'))),
            '2 matches'          => array('/[A-Z]/', 'Hello World', array(array('H', 'W'))),
            'no match'           => array('/HelloWorld/', 'Hello World', array()),
        );
    }

    public function providePregMatchCompilationError()
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
            'unmatched )'           => array(
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

    public function providePregMatchAllCompilationError()
    {
        return array(
            'incorrect delimiter'   => array(
                'Hello',
                'preg_match_all(): Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'no ending delimiter'   => array(
                '/Hello',
                'preg_match_all(): No ending delimiter \'/\' found; pattern: /Hello'
            ),
            'missing )'             => array(
                '/(Hello/',
                'preg_match_all(): Compilation failed: missing ) at offset 6; pattern: /(Hello/'
            ),
            'unmatched )'           => array(
                '/Hello)/',
                'preg_match_all(): Compilation failed: unmatched parentheses at offset 5; pattern: /Hello)/'
            ),
            'nothing to repeat'     => array(
                '/+/',
                'preg_match_all(): Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
            'unsupported \u'        => array(
                "/\uFFFF/",
                'preg_match_all(): Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid 2 octet utf-8' => array(
                "/\xc3\x28/u",
                "preg_match_all(): Compilation failed: invalid UTF-8 string at offset 0; pattern: /\xc3\x28/u"
            ),
            'unknown modifier'      => array(
                '//.',
                'preg_match_all(): Unknown modifier \'.\'; pattern: //.'
            ),
            'empty pattern'         => array(
                '',
                'preg_match_all(): Empty regular expression; pattern: '
            ),
        );
    }

    public function provideFailWithRuntimeError()
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
