<?php

namespace Gobie\Test\Regex\Drivers\Pcre;

use Gobie\Regex\Drivers\Pcre\PcreRegex;
use Gobie\Regex\RegexException;

class PcreRegexTest extends \PHPUnit_Framework_TestCase
{

    const SUBJECT = 'Hello World';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        ini_set('pcre.backtrack_limit', 100);
    }

    /**
     * @dataProvider provideMatch
     */
    public function testShouldMatch($pattern, $subject, $offset, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::match($pattern, $subject, $offset));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldMatchAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::match($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
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
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideGet
     */
    public function testShouldGet($pattern, $subject, $flags, $offset, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::get($pattern, $subject, $flags, $offset));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldGetAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::get($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
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
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideGetAll
     */
    public function testShouldGetAll($pattern, $subject, $flags, $offset, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::getAll($pattern, $subject, $flags, $offset));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldGetAllAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::getAll($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
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
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideReplace
     */
    public function testShouldReplace($pattern, $replacement, $subject, $limit, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::replace($pattern, $replacement, $subject, $limit));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldReplaceAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::replace($pattern, '', self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldReplaceAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::replace($pattern, '', $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideReplaceError
     */
    public function testShouldReplaceAndFail($pattern, $replacement, $subject, $limit, $exceptionMessage)
    {
        try {
            var_dump(PcreRegex::replace($pattern, $replacement, $subject, $limit));
            $this->fail('Exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideReplaceCallback
     */
    public function testShouldReplaceCallback($pattern, $callback, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::replaceCallback($pattern, $callback, $subject));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldReplaceCallbackAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::replaceCallback($pattern, function () {
            }, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldReplaceCallbackAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::replaceCallback($pattern, function () {
            }, $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }


    /**
     * @expectedException \PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Undefined variable: undef
     * @dataProvider             provideReplaceCallbackNotice
     */
    public function testShouldReplaceCallbackAndThrowNotice($pattern, $callback)
    {
        PcreRegex::ReplaceCallback($pattern, $callback, self::SUBJECT);
    }

    /**
     * @dataProvider provideSplit
     */
    public function testShouldSplit($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::split($pattern, $subject));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldSplitAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::split($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldSplitAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::split($pattern, $subject);
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideGrep
     */
    public function testShouldGrep($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::grep($pattern, $subject));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldGrepAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::grep($pattern, array(self::SUBJECT));
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideRuntimeError
     */
    public function testShouldGrepAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::grep($pattern, array($subject));
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @requires     function preg_filter
     * @dataProvider provideFilter
     */
    public function testShouldFilter($pattern, $replacement, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, PcreRegex::filter($pattern, $replacement, $subject));
    }

    /**
     * @requires     function preg_filter
     * @dataProvider provideCompilationError
     */
    public function testShouldFilterAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            PcreRegex::filter($pattern, '', array(self::SUBJECT));
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @requires     function preg_filter
     * @dataProvider provideRuntimeError
     */
    public function testShouldFilterAndFailWithRuntimeError($pattern, $subject, $exceptionMessage)
    {
        try {
            PcreRegex::filter($pattern, '', array($subject));
            $this->fail('Runtime exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    public function testShouldShowPregMatchCompilationErrorDoesNotClearPregLastError()
    {
        try {
            // Runtime error
            PcreRegex::match('//u', "\xc3\x28");
        } catch (RegexException $e) {
            $this->assertSame('Malformed UTF-8 data; pattern: //u', $e->getShortMessage());
            $this->assertSame(\PREG_BAD_UTF8_ERROR, \preg_last_error());

            try {
                // Compilation error
                PcreRegex::match('/(/', '');
            } catch (RegexException $e) {
                $this->assertSame('Compilation failed: missing ) at offset 1; pattern: /(/', $e->getShortMessage());
                // Preg_last_error isn't cleared when compilation error occurs
                $this->assertSame(\PREG_BAD_UTF8_ERROR, \preg_last_error());

                return;
            }
        }

        $this->fail('Runtime and compilation errors should have occurred');
    }

    public function provideMatch()
    {
        return array(
            'full match'           => array('/^Hello\sWorld$/', self::SUBJECT, 0, true),
            'single match'         => array('/l/', self::SUBJECT, 0, true),
            '2 subgroups'          => array('/(Hello)\s(World)/', self::SUBJECT, 0, true),
            'no match'             => array('/HelloWorld/', self::SUBJECT, 0, false),
            'e at offset 1'        => array('/e/', self::SUBJECT, 1, true),
            'e not after offset 2' => array('/e/', self::SUBJECT, 2, false),
            'e not after H'        => array('/(?<!H)e/', self::SUBJECT, 1, false),
        );
    }

    public function provideGet()
    {
        return array(
            'full match'               => array('/^Hello\sWorld$/', self::SUBJECT, 0, 0, array('Hello World')),
            'single match'             => array('/l/', self::SUBJECT, 0, 0, array('l')),
            '2 subgroups'              => array(
                '/(Hello)\s(World)/',
                self::SUBJECT,
                0,
                0,
                array('Hello World', 'Hello', 'World')
            ),
            'no match'                 => array('/HelloWorld/', self::SUBJECT, 0, 0, array()),
            'uppercase after offset 1' => array('/[A-Z]/', self::SUBJECT, 0, 1, array('W')),
            'offset capture'           => array(
                '/[A-Z]/',
                self::SUBJECT,
                \PREG_OFFSET_CAPTURE,
                0,
                array(array('H', 0))
            ),
        );
    }

    public function provideGetAll()
    {
        return array(
            'full match'                => array('/^Hello\sWorld$/', self::SUBJECT, 0, 0, array(array('Hello World'))),
            'multiple matches'          => array('/l/', self::SUBJECT, 0, 0, array(array('l', 'l', 'l'))),
            '2 subgroups'               => array(
                '/(.)\s(.)/',
                self::SUBJECT,
                0,
                0,
                array(array('o W'), array('o'), array('W'))
            ),
            '2 matches'                 => array('/[A-Z]/', self::SUBJECT, 0, 0, array(array('H', 'W'))),
            'all'                       => array(
                '/(.)(\w+)(.)/',
                self::SUBJECT,
                0,
                0,
                array(
                    array('Hello ', 'World'),
                    array('H', 'W'),
                    array('ello', 'orl'),
                    array(' ', 'd')
                )
            ),
            'no match'                  => array('/HelloWorld/', self::SUBJECT, 0, 0, array()),
            'uppercase after offset 1'  => array('/[A-Z]/', self::SUBJECT, 0, 1, array(array('W'))),
            '2 subpatterns'             => array(
                '/([A-Z])(.)/',
                self::SUBJECT,
                0,
                0,
                array(array('He', 'Wo'), array('H', 'W'), array('e', 'o'))
            ),
            '2 subpatterns; set order'  => array(
                '/([A-Z])(.)/',
                self::SUBJECT,
                \PREG_SET_ORDER,
                0,
                array(array('He', 'H', 'e'), array('Wo', 'W', 'o'))
            ),
            'offset capture'            => array(
                '/[A-Z]/',
                self::SUBJECT,
                \PREG_OFFSET_CAPTURE,
                0,
                array(array(array('H', 0), array('W', 6)))
            ),
            'offset capture; set order' => array(
                '/[A-Z]/',
                self::SUBJECT,
                \PREG_OFFSET_CAPTURE | PREG_SET_ORDER,
                0,
                array(array(array('H', 0)), array(array('W', 6)))
            ),
        );
    }

    public function provideReplace()
    {
        return array(
            'no match'          => array('/HelloWorld/', '', self::SUBJECT, -1, 'Hello World'),
            'full replace'      => array('/^Hello\sWorld$/', 'Good day', self::SUBJECT, -1, 'Good day'),
            'multiple replaces' => array('/l/', '*', self::SUBJECT, -1, 'He**o Wor*d'),
            'array of patterns' => array(
                array('/[A-Z]/', '/[a-z]/'),
                array('U', 'u'),
                self::SUBJECT,
                -1,
                'Uuuuu Uuuuu'
            ),
            'array of subjects' => array('/t(\d+)/', 's\\1', array('t1', 'u2', 't3'), -1, array('s1', 'u2', 's3')),
            'use limit'         => array('/l/', '*', self::SUBJECT, 2, 'He**o World'),
        );
    }

    public function provideReplaceError()
    {
        return array(
            'string pattern and array replacement' => array(
                '/[A-Z]/',
                array(),
                '',
                -1,
                'Parameter mismatch, pattern is a string while replacement is an array; pattern: /[A-Z]/'
            ),
            'test'                                 => array(
                array('/[A-Z]/', '*', '/[a-z]/', '+'),
                '',
                '',
                -1,
                'No ending delimiter \'*\' found; pattern: /[A-Z]/, *, /[a-z]/, +'
            ),
        );
    }

    public function provideReplaceCallback()
    {
        return array(
            'full replace'           => array(
                '/^Hello\sWorld$/',
                function () {
                    return 'Good day';
                },
                self::SUBJECT,
                'Good day'
            ),
            'lowercase to uppercase' => array(
                '/[a-z]/',
                function ($matches) {
                    return \strtoupper($matches[0]);
                },
                self::SUBJECT,
                'HELLO WORLD'
            ),
            'full replace by groups' => array(
                '/^(\w+)\s(\w+)$/',
                function ($matches) {
                    return $matches[1] . '-' . $matches[2];
                },
                self::SUBJECT,
                'Hello-World'
            ),
            'replace each char'      => array(
                '/./',
                function ($matches) {
                    return ord($matches[0]);
                },
                self::SUBJECT,
                '721011081081113287111114108100'
            ),
            'no match'               => array(
                '/HelloWorld/',
                function () {
                    return '';
                },
                self::SUBJECT,
                'Hello World'
            ),

        );
    }

    public function provideReplaceCallbackNotice()
    {
        return array(
            'notice' => array(
                '/./',
                function () {
                    ++$undef;

                    return '';
                },
            ),
        );
    }

    public function provideSplit()
    {
        return array(
            'space separated' => array('/\s/', self::SUBJECT, array('Hello', 'World')),
            'on characters'   => array(
                '/(?<!^)(?!$)/',
                self::SUBJECT,
                array('H', 'e', 'l', 'l', 'o', ' ', 'W', 'o', 'r', 'l', 'd',)
            ),
            'no split'        => array('/\d/', self::SUBJECT, array('Hello World')),
        );
    }

    public function provideGrep()
    {
        return array(
            'all'             => array('/./', array('a', 'b', 'c'), array('a', 'b', 'c')),
            'space separated' => array('/\s/', array('a b', 'bc', 'c d'), array('a b', 2 => 'c d')),
            'none'            => array('/\d/', array('a b', 'bc', 'c d'), array()),
        );
    }

    public function provideFilter()
    {
        return array(
            'all'             => array('/./', '-', array('a', 'b', 'c'), array('-', '-', '-')),
            'space separated' => array('/\s/', '-', array('a b', 'bc', 'c d'), array('a-b', 2 => 'c-d')),
            'none'            => array('/\d/', '', array('a b', 'bc', 'c d'), array()),
        );
    }

    public function provideCompilationError()
    {
        return array(
            'incorrect delimiter'   => array(
                'Hello',
                'Delimiter must not be alphanumeric or backslash; pattern: Hello'
            ),
            'no ending delimiter'   => array(
                '/Hello',
                'No ending delimiter \'/\' found; pattern: /Hello'
            ),
            'missing ]'             => array(
                '/[a-z/',
                'Compilation failed: missing terminating ] for character class at offset 4; pattern: /[a-z/'
            ),
            'missing )'             => array(
                '/(Hello/',
                'Compilation failed: missing ) at offset 6; pattern: /(Hello/'
            ),
            'unmatched )'           => array(
                '/Hello)/',
                'Compilation failed: unmatched parentheses at offset 5; pattern: /Hello)/'
            ),
            'nothing to repeat'     => array(
                '/+/',
                'Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
            'unsupported \u'        => array(
                "/\uFFFF/",
                'Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 1; pattern: /\uFFFF/'
            ),
            'invalid 2 octet utf-8' => array(
                "/\xc3\x28/u",
                "Compilation failed: invalid UTF-8 string at offset 0; pattern: /\xc3\x28/u"
            ),
            'unknown modifier'      => array(
                '//.',
                'Unknown modifier \'.\'; pattern: //.'
            ),
            'empty pattern'         => array(
                '',
                'Empty regular expression; pattern: '
            )
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
                'aaaaaab',
                'Backtrack limit was exhausted; pattern: /(a*)*$/'
            ),
        );
    }
}
