<?php

namespace Gobie\Test\Regex\Wrappers;

use Gobie\Regex\Wrappers\RegexException;

abstract class RegexBaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Method name override in inherited class.
     *
     * @var array
     */
    public static $method;

    /**
     * @dataProvider provideSuccessBehavior
     */
    public function testShouldExecuteMethodAndAssertResult($args, $expectedResult)
    {
        $this->executeAndAssert(static::$method, $args, $expectedResult);
    }

    /**
     * @dataProvider provideErrorBehavior
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
            \call_user_func_array($method, $args);
            $this->fail('Exception should have been thrown');
        } catch (RegexException $ex) {
            $this->assertSame($exceptionMessage, $ex->getShortMessage());
        }
    }

    abstract public function provideSuccessBehavior();

    abstract public function provideErrorBehavior();

    protected function addCallback($original)
    {
        $modify = function ($add) {
            return function ($item) use ($add) {
                \array_splice($item[0], 1, 0, array($add));

                return $item;
            };
        };

        $keysWithCallback   = \array_map(function ($item) {
            return $item . ' with callback';
        }, \array_keys($original));
        $valuesWithCallback = \array_map($modify(function () {
        }), $original);

        $withCallback = \array_combine($keysWithCallback, $valuesWithCallback);
        $withString   = \array_map($modify(''), $original);

        return $withCallback + $withString;
    }
}
