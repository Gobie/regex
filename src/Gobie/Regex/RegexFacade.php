<?php

namespace Gobie\Regex;

/**
 * Facade over static drivers to use them in object method calls and DI.
 */
class RegexFacade
{

    const PCRE = '\Gobie\Regex\Drivers\Pcre\PcreRegex';

    const MB_EREG = '\Gobie\Regex\Drivers\Mb\MbRegex';

    /**
     * Class name of regex driver.
     *
     * @var string
     */
    private $driverClass;

    /**
     * @param string $driverClass Fully qualified class
     */
    public function __construct($driverClass)
    {
        $this->driverClass = $driverClass;
    }

    /**
     * Redirects object calls to static methods of the driver.
     *
     * @param string $name      Method
     * @param array  $arguments Arguments
     * @return mixed Whatever is returned from drivers
     * @throws \BadMethodCallException When driver doesn't implement requested functionality
     */
    public function __call($name, $arguments)
    {
        if (!\method_exists($this->driverClass, $name)) {
            throw new \BadMethodCallException('Method ' . $this->driverClass . '::' . $name . ' not implemented');
        }

        return \call_user_func_array(array($this->driverClass, $name), $arguments);
    }

    /**
     * Getter for driver class name.
     *
     * @return string
     */
    public function getDriverClass()
    {
        return $this->driverClass;
    }
}
