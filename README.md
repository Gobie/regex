Regex
=====

[![Build Status](https://travis-ci.org/Gobie/regex.png?branch=master)](https://travis-ci.org/Gobie/regex)
[![Coverage Status](https://coveralls.io/repos/Gobie/regex/badge.png?branch=master)](https://coveralls.io/r/Gobie/regex?branch=master)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/Gobie/regex/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/c4598e4dbfeea15c383c64690dffad95 "githalytics.com")](http://githalytics.com/Gobie/regex)

**Regex** is PHP library containing lightweight wrappers around regular expression libraries and extensions for day to day use.
We try to **resolve error related issues** those libraries expose and which they handle rather peculiarly.
We also gave a shot to **unifying API** they provide so library is meant to be [drop-in replacement](#unified-api) for
their existing counterparts for most of the usages.

Currently the most used regular expression library is PCRE. Since POSIX Extended implementation with its `ereg_*` functions
is deprecated and mbstring extension with its `mb_ereg_*` functions as an optional extension is not available everywhere.

Regex library implements wrappers for

 - **PCRE** library via PcreRegex class
 - **mbstring** extension via MbEregRegex class

Installation
------------

Download and install composer from `http://www.getcomposer.org/download`

Add the following to your project `composer.json` file

    {
        "require": {
            "gobie/regex": "dev-master"
        }
    }

When you're done just run `php composer.phar install` and the package is ready to be used.

Why should I care? / What problem does it solve?
------------------------------------------------

Regular expression libraries provide us set of functions we have to deal with.
They are somewhat similar and yet each slightly different from each other in covered functionality and error handling.

Take for instance PCRE library. Usual code seen in applications using it is

    if (preg_match($pattern, $subject, $matches)) {
        // do something with $matches if used at all
    }

This code is **always** correct as long as `$pattern` is not dynamically created and matching can never hit backtracking
or recursion limit and `$subject` is well formed UTF-8 (if UTF-8 is used) and *planets stay in conjunction*.

Two types of errors can happen here. We speak about compilation errors which trigger E_WARNING like backtracking and
recursion limit and input errors. Next there are runtime errors which are dealt with with `preg_last_error()`.
That is a lot of error handling to take care about.

More robust and less error-prone version:

    set_error_handler(function () {
        // deal with compilation error
    });

    if (preg_match($pattern, $subject, $matches)) {
        // do something with $matches if used at all
    }

    restore_error_handler();

    if (preg_last_error()) {
        // deal with runtime error
    }

Sometimes it is even crazier for example using `preg_replace_callback()` is just weird unless you want to make debugging difficult.
Usual code using it:

    if ($res = preg_replace_callback($pattern, $callback, $subject)) {
        // do something with $res
    }

Lots can happen here. Compilation and runtime error like above, but there can also be triggered errors from within `$callback`.
We can't just cover it by error handler since errors from within callback are not supposed to be caught by regex error handling.
So the correct solution which would catch compilation and runtime errors, but let other errors come through, could look like this:

    set_error_handler(function () {
        // deal with compilation error
    });

    preg_match($pattern, '');

    restore_error_handler();

    $res = preg_replace_callback($pattern, $callback, $subject);

    if ($res === null && preg_last_error()) {
        // deal with runtime error
    }

> Not to mention handling more complex cases like array of patterns.

**No way you are going to write all that!**

Usage / Here comes the solution
-------------------------------

This library solves error handling problem by doing all the heavy lifting in reusable manner.
Every error is handled by exception derived from `RegexException`. Example of the same code as above using Regex library:

    use Gobie\Regex\Drivers\Pcre\PcreRegex;
    use Gobie\Regex\Drivers\Pcre\PcreRegexException;

    // matching
    if (PcreRegex::match($pattern, $subject)) {
        // do something
    }

    // matching and parsing
    if ($matches = PcreRegex::get($pattern, $subject)) {
        // do something with $matches
    }

    // replace with callback
    if ($res = PcreRegex::replaceCallback($pattern, $callback, $subject)) {
        // do something with $res
    }

    // error handling
    try {
        if (PcreRegex::match($pattern, $subject)) {
            // do something
        }
    } catch (PcreRegexException $e) {
        // handle error
    }


**Once again quite readable with all the error handling you need.**

Unified API
-----------

Regular expression libraries provide variety of functions, but they are not replaceable out of the box.
So we tried to unify API across those libraries. There are several methods implemented with basic signature.

 - `(bool) match((string) $pattern, (string) $subject)`
 - `(array) get((string) $pattern, (string) $subject)`
 - `(array) getAll((string) $pattern, (string) $subject)`
 - `(string|array) replace((string|array) $pattern, (string|array) $replacement, (string|array) $subject)`
 - `(string|array) replaceCallback((string|array) ($pattern, (callable) $callback, (string|array) $subject)`
 - `(array) split((string) $pattern, (string) $subject)`
 - `(array) grep((string) $pattern, (array) $subject)`
 - `(string|array) filter((string|array) $pattern, (string|array) $replacement, (string|array) $subject)`

Specific library can add another arguments to signature or handle more types.

> For instance `PcreRegex::get()` adds `$flags` and `$offset` arguments to create signature
> `PcreRegex::get($pattern, $subject, $flags, $offset)`, but the basic arguments remain the same.

To accomplish this API, some methods had to be created in userland code.

> For instance mbstring doesn't have corresponding functions for PCRE functions `preg_match_all()`, `preg_grep()` or `preg_filter()`,
> thus methods like `MbEregRegex::getAll()`, `MbEregRegex::grep()` and `MbEregRegex::filter()` had to be created using mbstring primitives.

Requirements
------------

PHP 5.3.3 or above. Unit tests are regularly run against latest 5.3, 5.4, 5.5 and HHVM.
For `mb_ereg_replace_callback()` and thus for usage of `MbEregRegex::replaceCallback()` PHP 5.4 and above is required.

Note on HHVM
------------

Functions `preg_filter()` and `mb_ereg_replace_callback()` are not to date supported.
Some error messages have different format, mostly just added pattern which caused error.
Backtracking and recursion error messages are completely different and much more descriptive.
You can found out about these minor differences in unit test reports on [travis-ci](https://travis-ci.org/Gobie/regex).

FAQ
---

**Why is it implemented via static methods instead of nice object oriented way we all do and love?**

It is meant to be used as drop-in replacement for current usage of library/extension functions.

**But I want to use this as dependency in object oriented style, can I?**

No problem, for that case we have `RegexFacade` which just redirects object calls to given wrapper.
Usage looks like this

    $regex = new RegexFacade(RegexFacade::PCRE);
    if ($regex->match($pattern, $subject)) {
        // do something
    }

**But I don't want to use exceptions to handle regex errors. Why? I got my reasons. What can I do?**

Wrappers are prepared to be extended to overwrite error handling the you want.
For instance triggering errors instead of throwing exceptions can be implemented this way

    class MyPcreRegex extends PcreRegex
    {
        protected static function prepare($pattern)
        {
            set_error_handler(function ($_, $errstr) use ($pattern) {
                static::cleanup();
                trigger_error($errstr . '; ' . $pattern, E_USER_WARNING);
            });
        }

        protected static function handleError($pattern)
        {
            if ($error = preg_last_error()) {
                trigger_error(PcreRegexException::$messages[$error] . '; ' . $pattern, E_USER_WARNING);
            }
        }
    }

**I just want to use unified API without the error handling.**

    class NoErrorHandlingPcreRegex extends PcreRegex
    {
        protected static function prepare($pattern) {}
        protected static function cleanup() {}
        protected static function handleError($pattern) {}
    }

Contribute
----------

Contributions are always welcome as well as any questions or issues.
Unit testing is done via `phpunit` with configuration file `tests/complete.phpunit.xml`.
