<?php

namespace Gobie\Regex\Drivers\Pcre;

class PcreRegex
{

    /**
     * Regular expression match and return if pattern matches given subject.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $offset  Offset
     * @return bool True if pattern matches given subject, false otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-match.php
     */
    public static function match($pattern, $subject, $offset = 0)
    {
        self::prepare($pattern);
        $res = preg_match($pattern, $subject, $matches, 0, $offset);
        self::cleanup($pattern);

        return (bool) $res;
    }

    /**
     * Regular expression match and return first match.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $flags   Flags
     * @param int    $offset  Offset
     * @return array Array with first match that matches given subject, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-match.php
     */
    public static function get($pattern, $subject, $flags = 0, $offset = 0)
    {
        self::prepare($pattern);
        preg_match($pattern, $subject, $matches, $flags, $offset);
        self::cleanup($pattern);

        return $matches;
    }

    public static function getAll($pattern, $subject)
    {
        self::prepare($pattern);
        preg_match_all($pattern, $subject, $matches);
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

    public static function replaceCallback($pattern, $callback, $subject)
    {
        self::match($pattern, '');
        $res = \preg_replace_callback($pattern, $callback, $subject);

        if ($res === null && preg_last_error()) {
            throw new PcreRegexException(null, preg_last_error(), $pattern);
        }

        return $res;
    }

    public static function split($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \preg_split($pattern, $subject, -1, \PREG_SPLIT_DELIM_CAPTURE);
        self::cleanup($pattern);

        return $res;
    }

    public static function grep($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \preg_grep($pattern, $subject);
        self::cleanup($pattern);

        return $res;
    }

    public static function filter($pattern, $replacement, $subject)
    {
        self::prepare($pattern);
        $res = \preg_filter($pattern, $replacement, $subject);
        self::cleanup($pattern);

        return $res;
    }

    private static function prepare($pattern)
    {
        set_error_handler(function ($_, $errstr) use ($pattern) {
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
