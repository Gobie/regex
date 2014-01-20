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

    /**
     * @dataProvider provideTestShouldMatch
     */
    public function testShouldMatch($driver, $pattern, $subject, $expectedResult)
    {
        $regex = new RegexFacade($driver);
        $this->assertSame($regex->match($pattern, $subject), $expectedResult);
    }

    /**
     * @dataProvider provideTestShouldFailExecutingUnknownDriverMethod
     */
    public function testShouldFailExecutingUnknownDriverMethod($driver, $methodName, $exceptionMessage)
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

    public function provideTestShouldCreateRegexWithDriver()
    {
        return array(
            array(RegexFacade::PCRE),
            array(RegexFacade::MB_EREG),
        );
    }

    public function provideTestShouldMatch()
    {
        return array(
            array(RegexFacade::PCRE, '/abc/', '01abc23', true),
            array(RegexFacade::MB_EREG, 'abc', '01abc23', true),
        );
    }

    public function provideTestShouldFailExecutingUnknownDriverMethod()
    {
        return array(
            array(RegexFacade::PCRE, 'foo', 'Method \Gobie\Regex\Drivers\Pcre\PcreRegex::foo not implemented'),
            array(RegexFacade::MB_EREG, 'bar', 'Method \Gobie\Regex\Drivers\MbEreg\MbEregRegex::bar not implemented'),
        );
    }
}
