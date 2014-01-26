<?php

namespace Gobie\Regex\Drivers\Mb;

use Gobie\Regex\RegexException;

class MbRegex
{

    public static function match($pattern, $subject)
    {
        static::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern);
        $res = \mb_ereg_search();
        static::cleanup();

        return (bool) $res;
    }

    public static function get($pattern, $subject)
    {
        static::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern);
        $matches = \mb_ereg_search_regs();
        static::cleanup();

        return $matches ? : array();
    }

    public static function getAll($pattern, $subject)
    {
        static::prepare($pattern);

        $position   = 0;
        $subjectLen = \mb_strlen($subject);
        $matches    = array();

        \mb_ereg_search_init($subject, $pattern);
        while ($position !== false && $position < $subjectLen) {
            \mb_ereg_search_setpos($position);

            $result = \mb_ereg_search_regs();
            if ($result === false) {
                if (!$matches) {
                    $matches[] = array();
                }
                break;
            }

            foreach ($result as $key => $part) {
                $matches[$key][] = $part;
            }

            $position = \mb_ereg_search_getpos();
        }

        static::cleanup();

        return $matches;
    }

    public static function replace($pattern, $replacement, $subject)
    {
        static::prepare($pattern);
        $res = \mb_ereg_replace($pattern, $replacement, $subject);
        static::cleanup();

        return $res;
    }

    public static function replaceCallback($pattern, $callback, $subject)
    {
        static::prepare($pattern);
        $res = \mb_ereg_replace_callback($pattern, $callback, $subject);
        static::cleanup();

        return $res;
    }

    public static function split($pattern, $subject)
    {
        static::prepare($pattern);
        $res = \mb_split($pattern, $subject);
        static::cleanup();

        return $res;
    }

    public static function grep($pattern, $subject)
    {
        static::prepare($pattern);

        $matches = array();
        $counter = 0;
        foreach ((array) $subject as $sub) {
            \mb_ereg_search_init($sub, $pattern);
            if (\mb_ereg_search()) {
                $matches[$counter] = $sub;
            }
            ++$counter;
        }

        static::cleanup();

        return $matches;
    }

    public static function filter($pattern, $replacement, $subject)
    {
        static::prepare($pattern);

        $matches = array();
        $counter = 0;
        foreach ((array) $subject as $sub) {
            \mb_ereg_search_init($sub, $pattern);
            if (\mb_ereg_search()) {
                $matches[$counter] = \mb_ereg_replace($pattern, $replacement, $sub);
            }
            ++$counter;
        }

        static::cleanup();

        return $matches;
    }

    /**
     * @param $pattern
     */
    protected static function prepare($pattern)
    {
        \set_error_handler(function ($_, $errstr) use ($pattern) {
            restore_error_handler();
            throw new RegexException($errstr, null, $pattern);
        });
    }

    protected static function cleanup()
    {
        \restore_error_handler();
    }
}
