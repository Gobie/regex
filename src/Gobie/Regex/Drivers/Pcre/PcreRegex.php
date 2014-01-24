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

    /**
     * Global regular expression match and return all matches.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $flags   Flags
     * @param int    $offset  Offset
     * @return array Array of matches that match given subject, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-match-all.php
     */
    public static function getAll($pattern, $subject, $flags = \PREG_PATTERN_ORDER, $offset = 0)
    {
        self::prepare($pattern);
        preg_match_all($pattern, $subject, $matches, $flags, $offset);
        self::cleanup($pattern);

        return \array_filter($matches);
    }

    /**
     * Regular expression replace and return replaced.
     *
     * @param string|array $pattern     Pattern or array of patterns
     * @param string|array $replacement Replacement or array of replacements
     * @param string|array $subject     Subject or array of subjects
     * @param int          $limit       Limit of replacements
     * @return string|array Replaced subject or array of subjects
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-replace.php
     */
    public static function replace($pattern, $replacement, $subject, $limit = -1)
    {
        self::prepare($pattern);
        $res = \preg_replace($pattern, $replacement, $subject, $limit);
        self::cleanup($pattern);

        return $res;
    }

    /**
     * Regular expression replace using callback and return replaced.
     *
     * Compilation errors are caught by using patterns in preg_match.
     *
     * @param string|array $pattern  Pattern or array of patterns
     * @param callable     $callback Replace callback
     * @param string|array $subject  Subject or array of subjects
     * @param int          $limit    Limit of replacements
     * @return string|array Replaced subject or array of subjects
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-replace-callback.php
     */
    public static function replaceCallback($pattern, $callback, $subject, $limit = -1)
    {
        self::prepare($pattern);
        foreach ((array) $pattern as $pat) {
            preg_match($pat, '');
        }
        \restore_error_handler();

        $res = \preg_replace_callback($pattern, $callback, $subject, $limit);

        if ($res === null && preg_last_error()) {
            throw new PcreRegexException(null, preg_last_error(), implode(', ', (array) $pattern));
        }

        return $res;
    }

    /**
     * Regular expression split and return all parts.
     *
     * @param string $pattern Pattern
     * @param string $subject Subject
     * @param int    $limit   Limit
     * @param int    $flags   Flags defaults to PREG_SPLIT_DELIM_CAPTURE
     * @return array Array of splitted parts, array with original string otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-split.php
     */
    public static function split($pattern, $subject, $limit = -1, $flags = \PREG_SPLIT_DELIM_CAPTURE)
    {
        self::prepare($pattern);
        $res = \preg_split($pattern, $subject, $limit, $flags);
        self::cleanup($pattern);

        return $res;
    }

    /**
     * Regular expression grep and return matching items.
     *
     * @param string $pattern Pattern
     * @param array  $subject Array of subjects
     * @param int    $flags   Flags
     * @return array Array with items that matches given pattern, empty array otherwise
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-grep.php
     */
    public static function grep($pattern, $subject, $flags = 0)
    {
        self::prepare($pattern);
        $res = \preg_grep($pattern, $subject, $flags);
        self::cleanup($pattern);

        return $res;
    }

    /**
     * Regular expression filter and return only replaced.
     *
     * @param string|array $pattern     Pattern or array of patterns
     * @param string|array $replacement Replacement or array of replacements
     * @param string|array $subject     Subject or array of subjects
     * @param int          $limit       Limit of replacements
     * @return string|array Replaced subject or array of subjects
     * @throws PcreRegexException When compilation or runtime error occurs
     * @link http://php.net/manual/en/function.preg-filter.php
     */
    public static function filter($pattern, $replacement, $subject, $limit = -1)
    {
        self::prepare($pattern);
        $res = \preg_filter($pattern, $replacement, $subject, $limit);
        self::cleanup($pattern);

        return $res;
    }

    private static function prepare($pattern)
    {
        set_error_handler(function ($_, $errstr) use ($pattern) {
            restore_error_handler();
            throw new PcreRegexException($errstr, null, implode(', ', (array) $pattern));
        });
    }

    private static function cleanup($pattern)
    {
        restore_error_handler();

        if (preg_last_error()) {
            throw new PcreRegexException(null, preg_last_error(), implode(', ', (array) $pattern));
        }
    }
}
