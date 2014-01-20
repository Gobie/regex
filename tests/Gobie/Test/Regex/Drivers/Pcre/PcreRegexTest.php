<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Regex\Drivers\Pcre\PcreRegex;
use Gobie\Regex\RegexException;

class PcreRegexTest extends \PHPUnit_Framework_TestCase
{

    const SUBJECT = 'Hello World';

    /**
     * @dataProvider provideMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::match($pattern, $subject));
    }

    /**
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldMatchAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldMatchAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideGet
     */
    public function testShouldGet($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::get($pattern, $subject));
    }

    /**
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldGetAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::get($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldGetAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::get($pattern, $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideGetAll
     */
    public function testShouldGetAll($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::getAll($pattern, $subject));
    }

    /**
     * @dataProvider provideMatchAllCompilationError
     */
    public function testShouldGetAllAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::getAll($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldGetAllAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::getAll($pattern, $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideReplace
     */
    public function testShouldReplace($pattern, $replacement, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::Replace($pattern, $replacement, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldReplaceAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::Replace($pattern, '', self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldReplaceAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::Replace($pattern, '', $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    public function provideMatch()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', self::SUBJECT, true),
            'single match'       => array('/l/', self::SUBJECT, true),
            '2 subgroups'        => array('/(Hello)\s(World)/', self::SUBJECT, true),
            'no match'           => array('/HelloWorld/', self::SUBJECT, false),
        );
    }

    public function provideGet()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', self::SUBJECT, array('Hello World')),
            'single match'       => array('/l/', self::SUBJECT, array('l')),
            '2 subgroups'        => array('/(Hello)\s(World)/', self::SUBJECT, array('Hello World', 'Hello', 'World')),
            'no match'           => array('/HelloWorld/', self::SUBJECT, array()),
        );
    }

    public function provideGetAll()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', self::SUBJECT, array(array('Hello World'))),
            'multiple matches'   => array('/l/', self::SUBJECT, array(array('l', 'l', 'l'))),
            '2 subgroups'        => array('/(.)\s(.)/', self::SUBJECT, array(array('o W'), array('o'), array('W'))),
            '2 matches'          => array('/[A-Z]/', self::SUBJECT, array(array('H', 'W'))),
            'no match'           => array('/HelloWorld/', self::SUBJECT, array(array())),
        );
    }

    public function provideReplace()
    {
        return array(
            'simple hello world' => array('/Hello\sWorld/', 'Good day', self::SUBJECT, 'Good day'),
            'multiple matches'   => array('/l/', '*', self::SUBJECT, 'He**o Wor*d'),
            '2 matches'          => array('/[A-Z]/', '$', self::SUBJECT, '$ello $orld'),
            'no match'           => array('/HelloWorld/', '', self::SUBJECT, 'Hello World'),
        );
    }

    public function provideMatchCompilationError()
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

    public function provideMatchAllCompilationError()
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

    public function provideReplaceCompilationError()
    {
        return array(
            'incorrect delimiter'   => array(
                'Hello',
                'preg_replace(): Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'no ending delimiter'   => array(
                '/Hello',
                'preg_replace(): No ending delimiter \'/\' found; pattern: /Hello'
            ),
            'missing )'             => array(
                '/(Hello/',
                'preg_replace(): Compilation failed: missing ) at offset 6; pattern: /(Hello/'
            ),
            'unmatched )'           => array(
                '/Hello)/',
                'preg_replace(): Compilation failed: unmatched parentheses at offset 5; pattern: /Hello)/'
            ),
            'nothing to repeat'     => array(
                '/+/',
                'preg_replace(): Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
            'unsupported \u'        => array(
                "/\uFFFF/",
                'preg_replace(): Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid 2 octet utf-8' => array(
                "/\xc3\x28/u",
                "preg_replace(): Compilation failed: invalid UTF-8 string at offset 0; pattern: /\xc3\x28/u"
            ),
            'unknown modifier'      => array(
                '//.',
                'preg_replace(): Unknown modifier \'.\'; pattern: //.'
            ),
            'empty pattern'         => array(
                '',
                'preg_replace(): Empty regular expression; pattern: '
            ),
        );
    }

    public function provideRuntimeError()
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
