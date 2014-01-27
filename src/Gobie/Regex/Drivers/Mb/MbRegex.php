<?php

namespace Gobie\Regex\Drivers\Mb;

/**
 * Wrapper around mbstring extension.
 *
 * Usage:
 * <code>
 * if ($matches = MbRegex::getAll($pattern, $subject)) {
 *   // do stuff here with $matches
 * }
 * </code>
 *
 * @link http://php.net/book.mbstring.php
 */
class MbRegex
{

    /**
     * Regular expression match and return if pattern matches given subject.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param string $option  Option
     * @return bool True if pattern matches given subject, false otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search.php
     */
    public static function match($pattern, $subject, $option = "")
    {
        static::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern, $option);
        $res = \mb_ereg_search();
        static::cleanup();

        return (bool) $res;
    }

    /**
     * Regular expression match and return first match.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param string $option  Option
     * @return array Array with first match that matches given subject, empty array otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search-regs.php
     */
    public static function get($pattern, $subject, $option = "")
    {
        static::prepare($pattern);
        \mb_ereg_search_init($subject, $pattern, $option);
        $matches = \mb_ereg_search_regs();
        static::cleanup();

        return $matches ? : array();
    }

    /**
     * Global regular expression match and return all matches.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param string $option  Option
     * @return array Array of matches that match given subject, empty array otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search-regs.php
     */
    public static function getAll($pattern, $subject, $option = "")
    {
        static::prepare($pattern);

        $position   = 0;
        $subjectLen = \mb_strlen($subject);
        $matches    = array();

        \mb_ereg_search_init($subject, $pattern, $option);
        while ($position !== false && $position < $subjectLen) {
            \mb_ereg_search_setpos($position);

            $result = \mb_ereg_search_regs();
            if ($result === false) {
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

    /**
     * Regular expression replace and return replaced.
     *
     * @param string|string[]         $pattern     Pattern or array of patterns
     * @param string|callable|mixed[] $replacement Replacement (string or callback) or array of replacements
     * @param string|string[]         $subject     Subject or array of subjects
     * @param string                  $option      Option
     * @return string|string[] Replaced subject or array of subjects
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-replace.php
     * @link http://php.net/function.mb-ereg-replace-callback.php
     */
    public static function replace($pattern, $replacement, $subject, $option = "")
    {
        static::prepare($pattern);

        if (\is_array($pattern)) {
            if (\is_array($replacement)) {
                $replacement = \array_pad($replacement, \count($pattern), '');
            } else {
                $replacement = \array_fill(0, \count($pattern), $replacement);
            }
        } else {
            if (\is_array($replacement)) {
                \trigger_error('Parameter mismatch, pattern is a string while replacement is an array', \E_USER_WARNING);
            }

            $pattern     = (array) $pattern;
            $replacement = (array) $replacement;
        }

        $result = array();
        foreach ((array) $subject as $subjectPart) {
            $replacementPart = \reset($replacement);
            foreach ($pattern as $patternPart) {
                if (\is_callable($replacementPart)) {
                    $subjectPart = \mb_ereg_replace_callback($patternPart, $replacementPart, $subjectPart, $option);
                } else {
                    $subjectPart = \mb_ereg_replace($patternPart, $replacementPart, $subjectPart, $option);
                }
                $replacementPart = \next($replacement);
            }
            $result[] = $subjectPart;
        }

        static::cleanup();

        return \is_array($subject) ? ($result ? : $subject) : (\reset($result) ? : $subject);
    }

    /**
     * Regular expression split and return all parts.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $limit   Limit
     * @return string[] Array of splitted parts, array with original string otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-split.php
     */
    public static function split($pattern, $subject, $limit = -1)
    {
        static::prepare($pattern);
        $res = \mb_split($pattern, $subject, $limit);
        static::cleanup();

        return $res;
    }

    /**
     * Regular expression grep and return matching items.
     *
     * @param string          $pattern Pattern
     * @param string|string[] $subject Subject or array of subjects
     * @param string          $option  Option
     * @return string[] Array with items that matches given pattern, empty array otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search.php
     */
    public static function grep($pattern, $subject, $option = "")
    {
        static::prepare($pattern);

        $matches = array();
        $counter = 0;
        foreach ((array) $subject as $sub) {
            \mb_ereg_search_init($sub, $pattern, $option);
            if (\mb_ereg_search()) {
                $matches[$counter] = $sub;
            }
            ++$counter;
        }

        static::cleanup();

        return $matches;
    }

    /**
     * Regular expression filter and return only replaced.
     *
     * @param string          $pattern     Pattern
     * @param string          $replacement Replacement
     * @param string|string[] $subject     Subject or array of subjects
     * @param string          $option      Option
     * @return string[] Array of replaced subjects
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search.php
     * @link http://php.net/function.mb-ereg-replace.php
     */
    public static function filter($pattern, $replacement, $subject, $option = "")
    {
        static::prepare($pattern);

        $matches = array();
        $counter = 0;
        foreach ((array) $subject as $sub) {
            \mb_ereg_search_init($sub, $pattern, $option);
            if (\mb_ereg_search()) {
                $matches[$counter] = \mb_ereg_replace($pattern, $replacement, $sub, $option);
            }
            ++$counter;
        }

        static::cleanup();

        return $matches;
    }

    /**
     * Prepare error handler for catching compilation errors.
     *
     * @param string|string[] $pattern Pattern or array of patterns
     */
    protected static function prepare($pattern)
    {
        \set_error_handler(function ($errno, $errstr) use ($pattern) {
            \restore_error_handler();
            throw new MbRegexException($errstr, null, $pattern);
        });
    }

    /**
     * Clean up after prepare().
     */
    protected static function cleanup()
    {
        \restore_error_handler();
    }
}
