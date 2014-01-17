<?php

namespace Gobie\Test\Regex\Drivers;

use Gobie\Regex\Drivers\MbPosix\MbPosixRegex;
use Gobie\Regex\RegexException;

class MbPosixRegexTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideTestShouldMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame(MbPosixRegex::match($pattern, $subject), $expectedResult);
    }

    public function provideTestShouldMatch()
    {
        return array(
            'simple hello world' => array('Hello\sWorld', 'Hello World', array('Hello World')),
            '2 subgroups' => array('(Hello)\s(World)', 'Hello World', array('Hello World', 'Hello', 'World')),
            'no match' => array('HelloWorld', 'Hello World', array()),
        );
    }

    /**
     * @dataProvider provideTestShouldFailWithCompilationError
     */
    public function testShouldFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbPosixRegex::match($pattern, '');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }
        $this->fail('Compilation exception should have been thrown');
    }

    public function provideTestShouldFailWithCompilationError()
    {
        return array(
            'missing )'             => array(
                '(Hello',
                'mb_ereg(): mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'             => array(
                'Hello)',
                'mb_ereg(): mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat'     => array(
                '+',
                'mb_ereg(): mbregex compile err: target of repeat operator is not specified; pattern: +'
            ),
            'empty pattern'         => array(
                '',
                'mb_ereg(): empty pattern; pattern: '
            ),
        );
    }
}
