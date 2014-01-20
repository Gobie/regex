<?php

namespace Gobie\Regex\Drivers\MbEreg;

use Gobie\Regex\RegexException;

class MbEregRegex
{

    public static function match($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \mb_ereg($pattern, $subject);
        self::cleanup();

        return (bool) $res;
    }

    public static function get($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \mb_ereg($pattern, $subject, $matches);
        self::cleanup();

        return $res ? $matches : array();
    }

    public static function replace($pattern, $replacement, $subject)
    {
        self::prepare($pattern);
        $res = \mb_ereg_replace($pattern, $replacement, $subject);
        self::cleanup();

        return $res;
    }

    public static function replaceCallback($pattern, $callback, $subject)
    {
        self::prepare($pattern);
        $res = \mb_ereg_replace_callback($pattern, $callback, $subject);
        self::cleanup();

        return $res;
    }

    /**
     * @param $pattern
     */
    private static function prepare($pattern)
    {
        set_error_handler(function ($_, $errstr) use ($pattern) {
            restore_error_handler();
            throw new RegexException($errstr, null, $pattern);
        });
    }

    private static function cleanup()
    {
        restore_error_handler();
    }
}
