<?php

namespace Gobie\Test\Regex\Wrappers;

use Gobie\Regex\Wrappers\RegexFacade;

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
            array(RegexFacade::MB),
        );
    }

    public function provideTestShouldMatch()
    {
        return array(
            array(RegexFacade::PCRE, '/abc/', '01abc23', true),
            array(RegexFacade::MB, 'abc', '01abc23', true),
        );
    }

    public function provideTestShouldFailExecutingUnknownDriverMethod()
    {
        return array(
            array(RegexFacade::PCRE, 'foo', 'Method \Gobie\Regex\Wrappers\Pcre\PcreRegex::foo not implemented'),
            array(RegexFacade::MB, 'bar', 'Method \Gobie\Regex\Wrappers\Mb\MbRegex::bar not implemented'),
        );
    }
}
