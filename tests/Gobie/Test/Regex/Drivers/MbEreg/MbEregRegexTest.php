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
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldMatchAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::match($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
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
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldGetAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::get($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideReplace
     */
    public function testShouldReplace($pattern, $replacement, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::Replace($pattern, $replacement, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldReplaceAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::Replace($pattern, '', self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
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

    public function provideReplace()
    {
        return array(
            'simple hello world' => array('Hello\sWorld', 'Good day', self::SUBJECT, 'Good day'),
            'multiple matches'   => array('l', '*', self::SUBJECT, 'He**o Wor*d'),
            '2 matches'          => array('[A-Z]', '$', self::SUBJECT, '$ello $orld'),
            'no match'           => array('HelloWorld', '', self::SUBJECT, 'Hello World'),
        );
    }

    public function provideMatchCompilationError()
    {
        return array(
            'missing )'         => array(
                '(Hello',
                'mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'       => array(
                'Hello)',
                'mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat' => array(
                '+',
                'mbregex compile err: target of repeat operator is not specified; pattern: +'
            ),
            'empty pattern'     => array(
                '',
                'empty pattern; pattern: '
            ),
        );
    }

    public function provideReplaceCompilationError()
    {
        return array(
            'missing )'         => array(
                '(Hello',
                'mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'       => array(
                'Hello)',
                'mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat' => array(
                '+',
                'mbregex compile err: target of repeat operator is not specified; pattern: +'
            )
        );
    }

}
