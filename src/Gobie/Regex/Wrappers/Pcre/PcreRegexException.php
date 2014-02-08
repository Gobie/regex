<?php

namespace Gobie\Regex\Wrappers\Pcre;

use Gobie\Regex\Wrappers\RegexException;

class PcreRegexException extends RegexException
{

    public static $messages = array(
        \PREG_INTERNAL_ERROR        => 'Internal error',
        \PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
        \PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
        \PREG_BAD_UTF8_ERROR        => 'Malformed UTF-8 data',
        // HHVM fix, constant PREG_BAD_UTF8_OFFSET_ERROR replaced by its number
        5                           => 'The offset didn\'t correspond to the begin of a valid UTF-8 code point',
    );

    /**
     * If code is provided, but no message, it uses default PREG error messages.
     *
     * @param string               $message Message
     * @param int|null             $code    Code
     * @param string|string[]|null $pattern Pattern
     */
    public function __construct($message, $code = null, $pattern = null)
    {
        if (!$message && isset(self::$messages[$code])) {
            $message = self::$messages[$code];
        }

        parent::__construct($message, $code, $pattern);
    }
}
