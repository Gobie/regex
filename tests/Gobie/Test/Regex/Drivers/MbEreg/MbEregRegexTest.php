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
     * @dataProvider provideGetAll
     */
    public function testShouldGetAll($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::getAll($pattern, $subject));
    }

    /**
     * @dataProvider provideMatchCompilationError
     */
    public function testShouldGetAllAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::getAll($pattern, self::SUBJECT);
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
        $this->assertSame($expectedResult, MbEregRegex::replace($pattern, $replacement, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldReplaceAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::replace($pattern, '', self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @requires     function mb_ereg_replace_callback
     * @dataProvider provideReplaceCallback
     */
    public function testShouldReplaceCallback($pattern, $callback, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::replaceCallback($pattern, $callback, $subject));
    }

    /**
     * @requires     function mb_ereg_replace_callback
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldReplaceCallbackAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::replaceCallback($pattern, function () {
            }, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideSplit
     */
    public function testShouldSplit($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::split($pattern, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldSplitAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::split($pattern, self::SUBJECT);
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideGrep
     */
    public function testShouldGrep($pattern, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::grep($pattern, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldGrepAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::grep($pattern, array(self::SUBJECT));
            $this->fail('Compilation exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    /**
     * @dataProvider provideFilter
     */
    public function testShouldFilter($pattern, $replacement, $subject, $expectedResult)
    {
        $this->assertSame($expectedResult, MbEregRegex::filter($pattern, $replacement, $subject));
    }

    /**
     * @dataProvider provideReplaceCompilationError
     */
    public function testShouldFilterAndFailWithCompilationError($pattern, $exceptionMessage)
    {
        try {
            MbEregRegex::filter($pattern, '', array(self::SUBJECT));
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

    public function provideGetAll()
    {
        return array(
            'full match'       => array('^Hello\sWorld$', self::SUBJECT, array(array('Hello World'))),
            'multiple matches' => array('l', self::SUBJECT, array(array('l', 'l', 'l'))),
            '2 subgroups'      => array('(.)\s(.)', self::SUBJECT, array(array('o W'), array('o'), array('W'))),
            '2 matches'        => array('[A-Z]', self::SUBJECT, array(array('H', 'W'))),
            'all'              => array(
                '(.)(\w+)(.)',
                self::SUBJECT,
                array(
                    array('Hello ', 'World'),
                    array('H', 'W'),
                    array('ello', 'orl'),
                    array(' ', 'd')
                )
            ),
            'no match'         => array('HelloWorld', self::SUBJECT, array(array())),
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

    public function provideSplit()
    {
        return array(
            'space separated' => array('\s', self::SUBJECT, array('Hello', 'World')),
            'on l'            => array('l', self::SUBJECT, array('He', '', 'o Wor', 'd')),
            'no split'        => array('\d', self::SUBJECT, array('Hello World')),
        );
    }

    public function provideGrep()
    {
        return array(
            'all'             => array('.', array('a', 'b', 'c'), array('a', 'b', 'c')),
            'space separated' => array('\s', array('a b', 'bc', 'c d'), array('a b', 2 => 'c d')),
            'none'            => array('\d', array('a b', 'bc', 'c d'), array()),
        );
    }


    public function provideFilter()
    {
        return array(
            'all'             => array('.', '-', array('a', 'b', 'c'), array('-', '-', '-')),
            'space separated' => array('\s', '-', array('a b', 'bc', 'c d'), array('a-b', 2 => 'c-d')),
            'none'            => array('\d', '', array('a b', 'bc', 'c d'), array()),
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
