<?php

namespace Gobie\Regex\Wrappers\Mb;

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
        static::setUp($pattern);
        \mb_ereg_search_init($subject, $pattern, $option);
        $res = \mb_ereg_search();
        static::tearDown();

        return (bool) $res;
    }

    /**
     * Regular expression match and return first match.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param string $option  Option
     * @return string[] Array with first match that matches given subject, empty array otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search-regs.php
     */
    public static function get($pattern, $subject, $option = "")
    {
        static::setUp($pattern);
        \mb_ereg_search_init($subject, $pattern, $option);
        $matches = \mb_ereg_search_regs();
        static::tearDown();

        return $matches ? : array();
    }

    /**
     * Global regular expression match and return all matches.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param string $option  Option
     * @return string[][] Array of matches that match given subject, empty array otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search-regs.php
     */
    public static function getAll($pattern, $subject, $option = "")
    {
        static::setUp($pattern);

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

        static::tearDown();

        return $matches;
    }

    /**
     * Regular expression replace and return replaced.
     *
     * Warning, take care that callback does not trigger any errors or the PHP will just die with some weird exit code.
     *
     * @param string|string[]         $pattern     Pattern or array of patterns
     * @param callable|string|mixed[] $replacement Replacement (string or callback) or array of replacements
     * @param string|string[]         $subject     Subject or array of subjects
     * @param string                  $option      Option
     * @return string|string[] Replaced subject or array of subjects
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-replace.php
     * @link http://php.net/function.mb-ereg-replace-callback.php
     */
    public static function replace($pattern, $replacement, $subject, $option = "")
    {
        static::setUp($pattern);

        $replaceMap = self::prepareReplaceMap($pattern, $replacement);

        $result = array();
        foreach ((array) $subject as $key => $subjectPart) {
            foreach ($replaceMap as $item) {
                list($pattern, $replacement) = $item;

                $subjectPart = self::execReplace($pattern, $replacement, $subjectPart, $option);
            }
            $result[$key] = $subjectPart;
        }

        static::tearDown();

        return (\is_array($subject) ? $result : \reset($result)) ? : $subject;
    }

    /**
     * Regular expression split and return all parts.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $limit   Limit
     * @param string $option  Option
     * @return string[] Array of split parts, array with original string otherwise
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-split.php
     */
    public static function split($pattern, $subject, $option = '', $limit = -1)
    {
        static::setUp($pattern);

        $position     = 0;
        $lastPosition = 0;
        $res          = array();
        $subjectLen   = \mb_strlen($subject);

        do {
            \mb_ereg_search_init($subject, $pattern, $option);
            \mb_ereg_search_setpos($position);

            $matches = \mb_ereg_search_regs();
            if ($matches === false) {
                break;
            }

            $position     = \mb_ereg_search_getpos();
            $res[]        = \mb_substr($subject, $lastPosition, $position - \mb_strlen($matches[0]) - $lastPosition);
            $lastPosition = $position;
        } while ($position < $subjectLen && --$limit !== 1);

        if ($lastPosition <= $subjectLen) {
            $res[] = \mb_substr($subject, $lastPosition);
        }

        static::tearDown();

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
        static::setUp($pattern);

        $matches = array();
        foreach ((array) $subject as $key => $subjectPart) {
            \mb_ereg_search_init($subjectPart, $pattern, $option);
            if (\mb_ereg_search()) {
                $matches[$key] = $subjectPart;
            }
        }

        static::tearDown();

        return $matches;
    }

    /**
     * Regular expression filter and return only replaced.
     *
     * Warning, take care that callback does not trigger any errors or the PHP will just die with some weird exit code.
     *
     * @param string|string[]         $pattern     Pattern or array of patterns
     * @param callable|string|mixed[] $replacement Replacement (string or callback) or array of replacements
     * @param string|string[]         $subject     Subject or array of subjects
     * @param string                  $option      Option
     * @return string[] Array of filtered subjects
     * @throws MbRegexException When compilation error occurs
     * @link http://php.net/function.mb-ereg-search.php
     * @link http://php.net/function.mb-ereg-replace.php
     * @link http://php.net/function.mb-ereg-replace-callback.php
     */
    public static function filter($pattern, $replacement, $subject, $option = "")
    {
        static::setUp($pattern);

        $replaceMap = self::prepareReplaceMap($pattern, $replacement);

        $result = array();
        foreach ((array) $subject as $key => $subjectPart) {
            foreach ($replaceMap as $item) {
                list($pattern, $replacement) = $item;

                \mb_ereg_search_init($subjectPart, $pattern, $option);
                if (!\mb_ereg_search()) {
                    continue;
                }

                $subjectPart = self::execReplace($pattern, $replacement, $subjectPart, $option);
                $result[$key] = $subjectPart;
            }
        }

        static::tearDown();

        return $result;
    }

    /**
     * Prepare error handler for catching compilation errors.
     *
     * @param string|string[] $pattern Pattern or array of patterns
     */
    protected static function setUp($pattern)
    {
        \set_error_handler(function ($errno, $errstr) use ($pattern) {
            \restore_error_handler();
            throw new MbRegexException($errstr, null, $pattern);
        });
    }

    /**
     * Clean up after setUp().
     */
    protected static function tearDown()
    {
        \restore_error_handler();
    }

    /**
     * Prepare map (pattern, replacement) from arguments.
     *
     * @param string|string[]         $pattern     Pattern or array of patterns
     * @param callable|string|mixed[] $replacement Replacement (string or callback) or array of replacements
     * @return string[][] Array of pattern and replacement
     */
    private static function prepareReplaceMap($pattern, $replacement)
    {
        $isPatternArray     = \is_array($pattern);
        $isReplacementArray = \is_array($replacement) && !\is_callable($replacement);

        if (!$isPatternArray && $isReplacementArray) {
            \trigger_error('Parameter mismatch, pattern is a string while replacement is an array', \E_USER_WARNING);
        }

        if ($isPatternArray) {
            $replacement = $isReplacementArray
                ? \array_pad($replacement, \count($pattern), '')
                : \array_fill(0, \count($pattern), $replacement);
        } else {
            $pattern     = (array) $pattern;
            $replacement = (array) $replacement;
        }

        return \array_map(null, $pattern, $replacement);
    }

    /**
     * Replace subject by callback or by string.
     *
     * @param string          $pattern     Pattern
     * @param callable|string $replacement Replacement
     * @param string          $subject     Subject
     * @param string          $option      Option
     * @return string Replaced subject
     */
    private static function execReplace($pattern, $replacement, $subject, $option)
    {
        return (\is_object($replacement) || \is_array($replacement)) && \is_callable($replacement)
            ? \mb_ereg_replace_callback($pattern, $replacement, $subject, $option)
            : \mb_ereg_replace($pattern, $replacement, $subject, $option);
    }
}
