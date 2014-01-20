<?php

namespace Gobie\Regex\Drivers\Pcre;

class PcreRegex
{

    public static function match($pattern, $subject)
    {
        self::prepare($pattern);
        $res = preg_match($pattern, $subject);
        self::cleanup($pattern);

        return (bool) $res;
    }

    public static function get($pattern, $subject)
    {
        self::prepare($pattern);
        $res = preg_match($pattern, $subject, $matches);
        self::cleanup($pattern);

        return $matches;
    }

    public static function getAll($pattern, $subject)
    {
        self::prepare($pattern);
        $res = preg_match_all($pattern, $subject, $matches);
        self::cleanup($pattern);

        return $matches;
    }

    public static function replace($pattern, $replacement, $subject)
    {
        self::prepare($pattern);
        $res = \preg_replace($pattern, $replacement, $subject);
        self::cleanup($pattern);

        return $res;
    }

    private static function prepare($pattern)
    {
        set_error_handler(function ($errno, $errstr) use ($pattern) {
            restore_error_handler();
            throw new PcreRegexException($errstr, null, $pattern);
        });
    }

    private static function cleanup($pattern)
    {
        restore_error_handler();

        if (preg_last_error()) {
            throw new PcreRegexException(null, preg_last_error(), $pattern);
        }
    }
}
