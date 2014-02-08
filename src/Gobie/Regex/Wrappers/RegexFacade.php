<?php

namespace Gobie\Regex\Wrappers;

/**
 * Facade over static Wrappers to use them in object method calls and DI.
 *
 * @method bool match($pattern, $subject)
 * @method string get($pattern, $subject)
 * @method string[][] getAll($pattern, $subject)
 * @method string|string[] replace($pattern, $replacement, $subject)
 * @method string[] split($pattern, $subject)
 * @method string[] grep($pattern, $subject)
 * @method string[] filter($pattern, $replacement, $subject)
 */
class RegexFacade
{

    const PCRE = '\Gobie\Regex\Wrappers\Pcre\PcreRegex';

    const MB = '\Gobie\Regex\Wrappers\Mb\MbRegex';

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
