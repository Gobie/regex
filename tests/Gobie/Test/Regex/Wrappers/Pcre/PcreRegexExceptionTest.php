<?php
namespace Gobie\Test\Regex\Wrappers\Pcre;

use Gobie\Regex\Wrappers\Pcre\PcreRegexException;

class PcreRegexExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideCreation
     */
    public function testCreation($message, $code, $pattern, $expectedMessage)
    {
        $exception = new PcreRegexException($message, $code, $pattern);
        $this->assertSame($expectedMessage, $exception->getMessage());
    }

    public function provideCreation()
    {
        return array(
            'unknown error' => array(
                '',
                null,
                null,
                'Unknown error'
            ),
            'system error'  => array(
                '',
                \PREG_BAD_UTF8_ERROR,
                null,
                'Malformed UTF-8 data'
            ),
        );
    }
}
