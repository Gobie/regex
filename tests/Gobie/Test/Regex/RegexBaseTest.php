<?php

namespace Gobie\Test\Regex;

use Gobie\Regex\RegexException;

abstract class RegexBaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Method name override in inherited class.
     *
     * @var array
     */
    public static $method;

    /**
     * @dataProvider provideExecuteAndAssert
     */
    public function testShouldExecuteMethodAndAssertResult($args, $expectedResult)
    {
        $this->executeAndAssert(static::$method, $args, $expectedResult);
    }

    /**
     * @dataProvider provideCompilationError
     */
    public function testShouldFailWithCompilationError($args, $exceptionMessage)
    {
        $this->executeAndFail(static::$method, $args, $exceptionMessage);
    }

    protected function executeAndAssert($method, $args, $expectedResult)
    {
        $this->assertSame($expectedResult, call_user_func_array($method, $args));
    }

    protected function executeAndFail($method, $args, $exceptionMessage)
    {
        try {
            call_user_func_array($method, $args);
            $this->fail('Exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    abstract public function provideExecuteAndAssert();

    abstract public function provideCompilationError();
}
