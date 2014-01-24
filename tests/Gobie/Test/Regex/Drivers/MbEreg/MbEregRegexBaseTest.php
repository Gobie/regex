<?php

namespace Gobie\Test\Regex\Drivers\MbEreg;

use Gobie\Test\Regex\RegexBaseTest;

abstract class MbEregRegexBaseTest extends RegexBaseTest
{
    public function provideCompilationError()
    {
        return array(
            'missing ]'         => array(
                array('[a-z', ''),
                'mbregex compile err: premature end of char-class; pattern: [a-z'
            ),
            'missing )'         => array(
                array('(Hello', ''),
                'mbregex compile err: end pattern with unmatched parenthesis; pattern: (Hello'
            ),
            'unmatched )'       => array(
                array('Hello)', ''),
                'mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
            'nothing to repeat' => array(
                array('+', ''),
                'mbregex compile err: target of repeat operator is not specified; pattern: +'
            ),
        );
    }
}
