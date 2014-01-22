<?php

namespace Gobie\Regex\Drivers\MbEreg;

use Gobie\Regex\RegexException;

class MbEregRegex
{

    public static function match($pattern, $subject)
    {
        self::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern);
        $res = \mb_ereg_search();
        self::cleanup();

        return (bool) $res;
    }

    public static function get($pattern, $subject)
    {
        self::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern);
        $matches = \mb_ereg_search_regs();
        self::cleanup();

        return $matches ? : array();
    }

    public static function getAll($pattern, $subject)
    {
        self::prepare($pattern);

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

        self::cleanup();

        return $matches;
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

    public static function split($pattern, $subject)
    {
        self::prepare($pattern);
        $res = \mb_split($pattern, $subject);
        self::cleanup();

        return $res;
    }

    public static function grep($pattern, $subject)
    {
        self::prepare($pattern);

        $matches = array();
        $counter = 0;
        foreach ((array)$subject as $sub) {
            \mb_ereg_search_init($sub, $pattern);
            if (\mb_ereg_search()) {
                $matches[$counter] = $sub;
            }
            ++$counter;
        }

        self::cleanup();

        return $matches;
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
