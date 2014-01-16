<?php

namespace Gobie\Regex;

class RegexException extends \Exception
{

    public static $messages = array(
        PREG_INTERNAL_ERROR        => 'Internal error',
        PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
        PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
        PREG_BAD_UTF8_ERROR        => 'Malformed UTF-8 data',
        PREG_BAD_UTF8_OFFSET_ERROR => 'The offset didn\'t correspond to the begin of a valid UTF-8 code point',
    );

    public function __construct($message, $code = null, $pattern = null)
    {
        if (!$message) {
            $message = isset(self::$messages[$code]) ? self::$messages[$code] : 'Unknown error';
        }

        if ($pattern !== null) {
            $message .= '; pattern: ' . $pattern;
        }

        parent::__construct($message, $code);
    }
}
