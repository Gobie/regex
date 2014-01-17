<?php

namespace Gobie\Regex\Drivers\MbPosix;

use Gobie\Regex\RegexException;

class MbPosixRegex
{

    public static function get($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \mb_ereg($pattern, $subject, $matches);
        self::cleanup();

        return $res ? $matches : array();
    }

    /**
     * @param $pattern
     */
    private static function prepare($pattern)
    {
        set_error_handler(function ($errno, $errstr) use ($pattern) {
            restore_error_handler();
            throw new RegexException($errstr, null, $pattern);
        });
    }

    private static function cleanup()
    {
        restore_error_handler();
    }
}
