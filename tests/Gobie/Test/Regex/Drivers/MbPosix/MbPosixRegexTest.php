<?php

namespace Gobie\Test\Regex\Drivers;

use Gobie\Regex\Drivers\MbPosix\MbPosixRegex;
use Gobie\Regex\RegexException;

class MbPosixRegexTest extends \PHPUnit_Framework_TestCase
{

    const SUBJECT = 'Hello World';

    /**
     * @dataProvider provideTestShouldGet
     */
    public function testShouldGet($pattern, $subject, $expectedResult)
    {
        $this->assertSame(MbPosixRegex::get($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider provideTestShouldMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame(MbPosixRegex::match($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider provideGetCompilationError
     */
    public function testShouldTryToGetButFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbPosixRegex::get($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldTryToMatchButFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbPosixRegex::match($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    public function provideTestShouldGet()
    {
        return array(
            'simple hello world' => array('Hello\sWorld', self::SUBJECT, array('Hello World')),
            '2 subgroups'        => array('(Hello)\s(World)', self::SUBJECT, array('Hello World', 'Hello', 'World')),
            'no match'           => array('HelloWorld', self::SUBJECT, array()),
        );
    }

    public function provideTestShouldMatch()
    {
        return array(
            'empty pattern'      => array('', self::SUBJECT, true),
            'simple hello world' => array('Hello\sWorld', self::SUBJECT, true),
            '2 subgroups'        => array('(Hello)\s(World)', self::SUBJECT, true),
            'no match'           => array('HelloWorld', self::SUBJECT, false),
        );
    }

    public function provideGetCompilationError()
    {
        return array(
            'missing )'         => array(
                '(Hello',
                'mb_ereg(): mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'       => array(
                'Hello)',
                'mb_ereg(): mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat' => array(
                '+',
                'mb_ereg(): mbregex compile err: target of repeat operator is not specified; pattern: +'
            ),
            'empty pattern'     => array(
                '',
                'mb_ereg(): empty pattern; pattern: '
            ),
        );
    }

    public function provideMatchCompilationError()
    {
        return array(
            'missing )'         => array(
                '(Hello',
                'mb_ereg_match(): mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'       => array(
                'Hello)',
                'mb_ereg_match(): mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat' => array(
                '+',
                'mb_ereg_match(): mbregex compile err: target of repeat operator is not specified; pattern: +'
            )
        );
    }
}
