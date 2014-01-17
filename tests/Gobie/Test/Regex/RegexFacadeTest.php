<?php

namespace Gobie\Test\RegexFacade;

use Gobie\Regex\RegexFacade;

class RegexFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideTestShouldCreateRegexWithDriver
     */
    public function testShouldCreateRegexWithDriver($driver)
    {
        $regex = new RegexFacade($driver);
        $this->assertSame($regex->getDriverClass(), $driver);
    }

    public function provideTestShouldCreateRegexWithDriver()
    {
        return array(
            array(RegexFacade::PCRE),
            array(RegexFacade::MB_POSIX),
        );
    }

    /**
     * @dataProvider provideTestShouldMatch
     */
    public function testShouldMatch($driver, $pattern, $subject, $expectedResult)
    {
        $regex = new RegexFacade($driver);
        $this->assertSame($regex->match($pattern, $subject), $expectedResult);
    }

    public function provideTestShouldMatch()
    {
        return array(
            array(RegexFacade::PCRE, '/abc/', '01abc23', array('abc')),
            array(RegexFacade::MB_POSIX, 'abc', '01abc23', array('abc')),
        );
    }

    /**
     * @dataProvider provideTestShouldFailExecuteUnknownDriverMethod
     */
    public function testShouldFailExecuteUnknownDriverMethod($driver, $methodName, $exceptionMessage)
    {
        try {
            $regex = new RegexFacade($driver);
            \call_user_func(array($regex, $methodName));
        } catch (\BadMethodCallException $ex) {
            $this->assertSame($exceptionMessage, $ex->getMessage());

            return;
        }

        $this->fail();
    }

    public function provideTestShouldFailExecuteUnknownDriverMethod()
    {
        return array(
            array(RegexFacade::PCRE, 'foo', 'Method \Gobie\Regex\Drivers\Pcre\PcreRegex::foo not implemented'),
            array(RegexFacade::MB_POSIX, 'bar', 'Method \Gobie\Regex\Drivers\MbPosix\MbPosixRegex::bar not implemented'),
        );
    }
}
