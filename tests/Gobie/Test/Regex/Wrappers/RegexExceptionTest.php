<?php

namespace Gobie\Test\Regex\Wrappers;

use Gobie\Regex\Wrappers\RegexException;

class RegexExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideMessage
     */
    public function testShouldGetMessage($message, $code, $pattern, $expectedMessage)
    {
        $exception = new RegexException($message, $code, $pattern);
        $this->assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @dataProvider provideShortMessage
     */
    public function testShouldGetShortMessage($message, $code, $pattern, $expectedMessage)
    {
        $exception = new RegexException($message, $code, $pattern);
        $this->assertSame($expectedMessage, $exception->getShortMessage());
    }

    public function provideMessage()
    {
        return array(
            'nothing'             => array(
                null,
                null,
                null,
                'Unknown error'
            ),
            'only message'        => array(
                'message',
                null,
                null,
                'message'
            ),
            'message and pattern' => array(
                'message',
                null,
                '/+/',
                'message; pattern: /+/'
            ),
            'only pattern'        => array(
                null,
                null,
                '/+/',
                'Unknown error; pattern: /+/'
            ),
        );
    }

    public function provideShortMessage()
    {
        return array(
            'with function name'    => array(
                'mb_ereg(): mbregex compile err: something incorrect',
                null,
                '[a-z',
                'mbregex compile err: something incorrect; pattern: [a-z'
            ),
            'without function name' => array(
                'Compilation failed: nothing to repeat at offset 0',
                null,
                '/+/',
                'Compilation failed: nothing to repeat at offset 0; pattern: /+/'
            ),
        );
    }
}
