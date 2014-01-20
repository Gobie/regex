<?php

namespace Gobie\Test\Regex\Drivers;

use Gobie\Regex\Drivers\MbEreg\MbEregRegex;
use Gobie\Regex\RegexException;

class MbEregRegexTest extends \PHPUnit_Framework_TestCase
{

    const SUBJECT = 'Hello World';

    /**
     * @dataProvider provideMatch
     */
    public function testShouldMatch($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::match($pattern, $subject));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldMatchAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::match($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    /**
     * @dataProvider provideGet
     */
    public function testShouldGet($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::get($pattern, $subject));
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldGetAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::get($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());
        }
    }

    public function provideMatch()
    {
        return array(
            'simple hello world' => array('Hello\sWorld', self::SUBJECT, true),
            'single match'       => array('l', self::SUBJECT, true),
            '2 subgroups'        => array('(Hello)\s(World)', self::SUBJECT, true),
            'no match'           => array('HelloWorld', self::SUBJECT, false),
        );
    }

    public function provideGet()
    {
        return array(
            'simple hello world' => array('Hello\sWorld', self::SUBJECT, array('Hello World')),
            'single match'       => array('l', self::SUBJECT, array('l')),
            '2 subgroups'        => array('(Hello)\s(World)', self::SUBJECT, array('Hello World', 'Hello', 'World')),
            'no match'           => array('HelloWorld', self::SUBJECT, array()),
        );
    }

    public function provideCompilationError()
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

}
