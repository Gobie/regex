<?php

namespace Gobie\Regex\Drivers\Pcre;

class PcreRegex
{

    public static function get($pattern, $subject)
    {
        set_error_handler(function ($errno, $errstr) use ($pattern) {
            restore_error_handler();
            throw new PcreRegexException($errstr, null, $pattern);
        });

        $res = preg_match($pattern, $subject, $matches);

        restore_error_handler();

        if ($res === false && preg_last_error()) {
            throw new PcreRegexException(null, preg_last_error(), $pattern);
        }

        return $res ? $matches : array();
    }
}