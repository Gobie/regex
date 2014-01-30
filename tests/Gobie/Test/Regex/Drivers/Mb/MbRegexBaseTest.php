<?php

namespace Gobie\Test\Regex\Drivers\Mb;

use Gobie\Test\Regex\RegexBaseTest;

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
