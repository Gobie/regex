<?php

namespace Gobie\Test\Regex\Drivers;

use Gobie\Regex\Drivers\MbEreg\MbEregRegex;
use Gobie\Regex\RegexException;

/**
 * @requires extension mbstring
 */
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

    /**
     * @requires     PHP 5.4.1
     * @dataProvider provideReplaceCallback
     */
    public function testShouldReplaceCallback($pattern, $callback, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::ReplaceCallback($pattern, $callback, $subject));
    }

    /**
     * @requires     PHP 5.4.1
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldReplaceCallbackAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::ReplaceCallback($pattern, function () {
            }, self::SUBJECT);
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
            'empty pattern'      => array('', '-', self::SUBJECT, '-H-e-l-l-o- -W-o-r-l-d-'),
            'no match'           => array('HelloWorld', '', self::SUBJECT, 'Hello World'),
        );
    }

    public function provideReplaceCallback()
    {
        return array(
            'full replace'           => array(
                '^Hello\sWorld$',
                function () {
                    return 'Good day';
                },
                self::SUBJECT,
                'Good day'
            ),
            'lowercase to uppercase' => array(
                '[a-z]',
                function ($matches) {
                    return \strtoupper($matches[0]);
                },
                self::SUBJECT,
                'HELLO WORLD'
            ),
            'full replace by groups' => array(
                '^(\w+)\s(\w+)$',
                function ($matches) {
                    return $matches[1] . '-' . $matches[2];
                },
                self::SUBJECT,
                'Hello-World'
            ),
            'replace each char'      => array(
                '.',
                function ($matches) {
                    return ord($matches[0]);
                },
                self::SUBJECT,
                '721011081081113287111114108100'
            ),
            'no match'               => array(
                'HelloWorld',
                function () {
                    return '';
                },
                self::SUBJECT,
                'Hello World'
            ),
        );
    }

    public function provideMatchCompilationError()
    {
        return array(
            'missing ]'         => array(
                '[a-z',
                'mbregex compile err: premature end of char-class; pattern: [a-z'
            ),
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
                'Empty pattern; pattern: '
            ),
        );
    }

    public function provideReplaceCompilationError()
    {
        return array(
            'missing ]'         => array(
                '[a-z',
                'mbregex compile err: premature end of char-class; pattern: [a-z'
            ),
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
        );
    }

}
