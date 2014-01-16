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
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Method \Gobie\Regex\Drivers\PcreRegex::unmatch not implemented
     */
    public function testShouldFailExecuteUnknownDriverMethod()
    {
        $regex = new RegexFacade(RegexFacade::PCRE);
        $regex->unmatch();
    }
}
