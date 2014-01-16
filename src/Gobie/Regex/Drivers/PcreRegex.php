<?php

namespace Gobie\Regex\Drivers;

use Gobie\Regex\RegexException;

class PcreRegex
{

    public static function match($pattern, $subject)
    {
        set_error_handler(function ($errno, $errstr) use ($pattern) {
            restore_error_handler();
            throw new RegexException($errstr, null, $pattern);
        });

        $res = preg_match($pattern, $subject, $matches);

        restore_error_handler();

        if ($res === false && preg_last_error()) {
            throw new RegexException(null, preg_last_error(), $pattern);
        }

        return $res ? $matches : array();
    }
}
