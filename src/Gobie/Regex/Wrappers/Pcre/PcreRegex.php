<?php

namespace Gobie\Regex\Wrappers\Pcre;

/**
 * Wrapper around PCRE library.
 *
 * It is meant to be replaceable for PCRE functions.
 *
 * Usage:
 * <code>
 * if ($matches = PcreRegex::getAll($pattern, $subject)) {
 *   // do stuff here with $matches
 * }
 * </code>
 *
 * @link http://php.net/pcre
 */
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
     * @link http://php.net/function.preg-match.php
     */
    public static function match($pattern, $subject, $offset = 0)
    {
        static::setUp($pattern);
        $res = \preg_match($pattern, $subject, $matches, 0, $offset);
        static::tearDown();
        static::handleError($pattern);

        return (bool) $res;
    }

    /**
     * Regular expression match and return first match.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $flags   Flags
     * @param int    $offset  Offset
     * @return string[] Array with first match that matches given subject, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-match.php
     */
    public static function get($pattern, $subject, $flags = 0, $offset = 0)
    {
        static::setUp($pattern);
        \preg_match($pattern, $subject, $matches, $flags, $offset);
        static::tearDown();
        static::handleError($pattern);

        return $matches;
    }

    /**
     * Global regular expression match and return all matches.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $flags   Flags
     * @param int    $offset  Offset
     * @return string[][] Array of matches that match given subject, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-match-all.php
     */
    public static function getAll($pattern, $subject, $flags = \PREG_PATTERN_ORDER, $offset = 0)
    {
        static::setUp($pattern);
        \preg_match_all($pattern, $subject, $matches, $flags, $offset);
        static::tearDown();
        static::handleError($pattern);

        return \array_filter($matches);
    }

    /**
     * Regular expression replace and return replaced.
     *
     * @param string|string[]          $pattern     Pattern or array of patterns
     * @param callable|string|string[] $replacement Replacement (string or callable) or array of string replacements
     * @param string|string[]          $subject     Subject or array of subjects
     * @param int                      $limit       Limit of replacements
     * @return string|string[] Replaced subject or array of subjects
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-replace.php
     * @link http://php.net/function.preg-replace-callback.php
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1)
    {
        static::setUp($pattern);

        if ((\is_object($replacement) || \is_array($replacement)) && \is_callable($replacement)) {
            foreach ((array) $pattern as $patternPart) {
                \preg_match($patternPart, '');
            }

            static::tearDown();

            $res = \preg_replace_callback($pattern, $replacement, $subject, $limit);

            if ($res === null) {
                static::handleError($pattern);
            }

            return $res;
        }

        $res = \preg_replace($pattern, $replacement, $subject, $limit);

        static::tearDown();
        static::handleError($pattern);

        return $res;
    }

    /**
     * Regular expression split and return all parts.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $limit   Limit
     * @param int    $flags   Flags defaults to PREG_SPLIT_DELIM_CAPTURE
     * @return string[] Array of split parts, array with original string otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-split.php
     */
    public static function split($pattern, $subject, $limit = -1, $flags = \PREG_SPLIT_DELIM_CAPTURE)
    {
        static::setUp($pattern);
        $res = \preg_split($pattern, $subject, $limit, $flags);
        static::tearDown();
        static::handleError($pattern);

        return $res;
    }

    /**
     * Regular expression grep and return matching items.
     *
     * @param string          $pattern Pattern
     * @param string|string[] $subject Subject or array of subjects
     * @param int             $flags   Flags
     * @return string[] Array with items that matches given pattern, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-grep.php
     */
    public static function grep($pattern, $subject, $flags = 0)
    {
        static::setUp($pattern);
        $res = \preg_grep($pattern, (array) $subject, $flags);
        static::tearDown();
        static::handleError($pattern);

        return $res;
    }

    /**
     * Regular expression filter and return only replaced.
     *
     * @param string|string[] $pattern     Pattern or array of patterns
     * @param string|string[] $replacement Replacement or array of replacements
     * @param string|string[] $subject     Subject or array of subjects
     * @param int             $limit       Limit of replacements
     * @return string|string[] Replaced subject or array of subjects
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/function.preg-filter.php
     */
    public static function filter($pattern, $replacement, $subject, $limit = -1)
    {
        static::setUp($pattern);
        $res = \preg_filter($pattern, $replacement, $subject, $limit);
        static::tearDown();
        static::handleError($pattern);

        return $res;
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
            throw new PcreRegexException($errstr, null, $pattern);
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
     * Handle runtime errors in PCRE.
     *
     * @param string|string[] $pattern Pattern or array of patterns
     * @throws PcreRegexException When runtime error occurs
     */
    protected static function handleError($pattern)
    {
        if ($error = \preg_last_error()) {
            throw new PcreRegexException(null, $error, $pattern);
        }
    }
}
