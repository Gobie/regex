<?php

namespace Gobie\Test\Regex\Wrappers\Mb;

use Gobie\Test\Regex\Wrappers\RegexBaseTest;

abstract class MbRegexBaseTest extends RegexBaseTest
{
    public function provideErrorBehavior()
    {
        return array(
            'unmatched )' => array(
                array('Hello)', ''),
                'mbregex compile err: unmatched close parenthesis; pattern: Hello)'
            ),
        );
    }
}
