<?php

namespace Gobie\Regex\Drivers\MbPosix;

use Gobie\Regex\RegexException;

class MbPosixRegex
{

    public static function match($pattern, $subject)
    {
        set_error_handler(function ($errno, $errstr) use ($pattern) {
            restore_error_handler();
            throw new RegexException($errstr, null, $pattern);
        });

        $res = \mb_ereg($pattern, $subject, $matches);

        restore_error_handler();

        return $res ? $matches : array();
    }
}
