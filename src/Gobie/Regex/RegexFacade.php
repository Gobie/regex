<?php

namespace Gobie\Regex;

class RegexFacade
{

    const PCRE = '\Gobie\Regex\Drivers\Pcre\PcreRegex';
    const MB_POSIX = '\Gobie\Regex\Drivers\MbPosix\MbPosixRegex';

    private $driverClass;

    public function __construct($driverClass)
    {
        $this->driverClass = $driverClass;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->driverClass, $name)) {
            throw new \BadMethodCallException('Method ' . $this->driverClass . '::' . $name . ' not implemented');
        }

        return call_user_func_array(array($this->driverClass, $name), $arguments);
    }

    public function getDriverClass()
    {
        return $this->driverClass;
    }
}