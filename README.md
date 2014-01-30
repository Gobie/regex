Regex
=====

[![Build Status](https://travis-ci.org/Gobie/regex.png?branch=master)](https://travis-ci.org/Gobie/regex)
[![Code Coverage](https://scrutinizer-ci.com/g/Gobie/regex/badges/coverage.png?s=6db26bc2741b7f0f5183f7afc50dcf306fbaabfc)](https://scrutinizer-ci.com/g/Gobie/regex/)
[![Latest Stable Version](https://poser.pugx.org/gobie/regex/v/stable.png)](https://packagist.org/packages/gobie/regex)
[![Total Downloads](https://poser.pugx.org/gobie/regex/downloads.png)](https://packagist.org/packages/gobie/regex)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Gobie/regex/badges/quality-score.png?s=0763e8dbf2953b656057f638413d6ab86a4d3be8)](https://scrutinizer-ci.com/g/Gobie/regex/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b90f6621-642e-491a-af0c-84e2e68bbb10/mini.png)](https://insight.sensiolabs.com/projects/b90f6621-642e-491a-af0c-84e2e68bbb10)
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/Gobie/regex/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/c4598e4dbfeea15c383c64690dffad95 "githalytics.com")](http://githalytics.com/Gobie/regex)

**Regex** is PHP library containing lightweight wrappers around regular expression libraries and extensions for day to day use.
We try to **resolve error related issues** those libraries expose and which they handle rather peculiarly.
We also gave a shot at **unifying API** they provide so library is meant to be [drop-in replacement](#unified-api) for
their existing counterparts for most of the usages.

Currently the most popular regular expression library is PCRE with its `preg_*` functions.
Mbstring extension with its `mb_ereg_*` functions as an optional extension is not available everywhere.
POSIX Extended implementation with its `ereg_*` functions is deprecated as of PHP 5.3.0 and should not be used.

Regex library implements wrappers for

 - **PCRE** library via PcreRegex class
 - **mbstring** extension via MbRegex class

Installation
------------

Download and install composer from `http://www.getcomposer.org/download`

Add the following to your project `composer.json` file

```json
{
    "require": {
        "gobie/regex": "dev-master"
    }
}
```

When you're done just run `php composer.phar install` and the package is ready to be used.

Unified API
-----------

Regular expression libraries provide variety of functions, but they are not replaceable out of the box.
So we tried to unify API across those libraries. There are several methods implemented with basic signature in all wrappers.
Pattern signature is different in each driver as PCRE is Perl-like and mbstring can change it on the fly with options.

 - `match($pattern, $subject)`
 - `get($pattern, $subject)`
 - `getAll($pattern, $subject)`
 - `replace($pattern, $replacement, $subject)`
 - `split($pattern, $subject)`
 - `grep($pattern, $subject)`
 - `filter($pattern, $replacement, $subject)`

Driver can add another arguments to signature or handle more types.

> For instance `PcreRegex::get()` adds `$flags` and `$offset` arguments to create signature
> `PcreRegex::get($pattern, $subject, $flags, $offset)`, but the basic arguments remain the same.

To accomplish this API, some methods had to be created in userland code.

> For instance mbstring doesn't have corresponding functions for PCRE functions `preg_match_all()`, `preg_grep()` or `preg_filter()`,
> thus methods like `MbRegex::getAll()`, `MbRegex::grep()` and `MbRegex::filter()` had to be created from scratch using mbstring primitives.

Examples
--------

This library solves error handling problems by doing all the heavy lifting in reusable manner.
Every error is handled by exception derived from `\Gobie\Regex\RegexException`.

```php
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
if ($res = PcreRegex::replace($pattern, $callback, $subject)) {
    // do something with $res
}

// error handling
try {
    // matching and parsing
    if ($matches = PcreRegex::getAll($pattern, $subject)) {
        // do something with $matches
    }
} catch (PcreRegexException $e) {
    // handle error
}
```

```php
use Gobie\Regex\Drivers\Mb\MbRegex;

// greping
if ($res = MbRegex::grep($pattern, $subject)) {
    // do something with $res
}

// splitting
if ($res = MbRegex::split($pattern, $subject)) {
    // do something with $res
}
```

**Once again quite readable with all the error handling you need.**

Requirements
------------

PHP 5.3.3 or above. Unit tests are regularly run against latest 5.3, 5.4, 5.5 and HHVM.
For `mb_ereg_replace_callback()` and thus for usage of `MbRegex::replace()` PHP 5.4 and above is required.

Note on HHVM
------------

Functions `preg_filter()` and `mb_ereg_replace_callback()` are not to date supported.
Some error messages have different format, mostly just added pattern which causes unit test error.
Backtracking and recursion error messages are completely different and much more descriptive.
You can find out about these differences in unit test reports on [travis-ci](https://travis-ci.org/Gobie/regex).

FAQ
---

**Why should I care? / What problem does it solve?**

Regular expression libraries provide us with set of functions we have to deal with.
They are somewhat similar and yet each slightly different in covered functionality and error handling.

Take for instance PCRE library. Common code seen in applications is

```php
if (preg_match($pattern, $subject, $matches)) {
    // do something with $matches if used at all
}
```

This code is correct as long as `$pattern` is not dynamically created and matching can never hit backtracking
or recursion limit and `$subject` as well as `$pattern` are well formed UTF-8 strings (if UTF-8 is used).

Two types of errors can happen here. We speak about compilation errors which trigger E_WARNING like input errors.
And runtime errors like hitting backtracking or recursion limit or encoding issues.
We can deal with those using `preg_last_error()` function.
But only if compilation error didn't happen, otherwise this function is unreliable as it doesn't clear its state.

More robust and less error-prone version:

```php
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
```

That's a lot of error handling to take care about, but it gets even more complicated.

For instance using `preg_replace_callback()` naively can make your life harder and put your debugging skills to test.
Usually, you use it the simplest way:

```php
if ($res = preg_replace_callback($pattern, $callback, $subject)) {
    // do something with $res
}
```

Lots can happen here, compilation and runtime errors shown above and also errors triggered from within `$callback`.
We just can't cover it with error handler like above, since errors from within callback should not be caught by regex error handling.
So the correct solution, which would catch compilation and runtime errors, but let the rest come through, could look like this:

```php
set_error_handler(function () {
    // deal with compilation error
});

preg_match($pattern, '');

restore_error_handler();

$res = preg_replace_callback($pattern, $callback, $subject);

if ($res === null && preg_last_error()) {
    // deal with runtime error
}
```

> Not to mention handling more complex cases like array of patterns.

**Why is it implemented via static methods instead of nice object oriented way we all use and love?**

It is meant to be used as drop-in replacement for current usage of library/extension functions.

*I want to use it as dependency in object oriented style, can I?**

No problem, for that case we have `RegexFacade` which just redirects object calls to given wrapper.

```php
$regex = new RegexFacade(RegexFacade::PCRE);
if ($regex->match($pattern, $subject)) {
    // do something
}

// is equivalent to

if (PcreRegex::match($pattern, $subject)) {
    // do something
}
```

**I don't want to use exceptions to handle regex errors. What can I do?**

Wrappers are prepared to be extended to overwrite anything the way you want.
For instance triggering errors instead of throwing exceptions can be implemented this way:

```php
class MyErrorHandlingPcreRegex extends PcreRegex
{
    protected static function setUp($pattern)
    {
        set_error_handler(function ($_, $errstr) use ($pattern) {
            static::tearDown(); // or restore_error_handler() for PHP 5.3
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
```

**I just want to use unified API without the error handling.**

Wrappers are easily extensible to accommodate any request.

```php
class NoErrorHandlingPcreRegex extends PcreRegex
{
    protected static function setUp($pattern) {}
    protected static function tearDown() {}
    protected static function handleError($pattern) {}
}
```

Performance
-----------

Performance is one of the main reasons we try to avoid regular expressions as much as possible.
It is much more efficient to use string function like `strpos`, `substr`, `str_replace`, `explode`, etc if possible.

But there are times we need regular expressions.
So we did a little benchmarking to show you, what does our abstraction take out of performance of using native functions.
We think that added functionality and usable error handling quite compensates the lost performance but decide for yourself.

> We also added string functions for comparison. They could accomplish roughly the same tasks in those test scenarios.

```
Gobie\Bench\PcreBench
    Method Name              Iterations    Average Time      Ops/second
    ----------------------  ------------  --------------    -------------
    libraryMatch          : [10,000    ] [0.0000313593864] [31,888.37900]
    libraryGet            : [10,000    ] [0.0000360656977] [27,727.17747]
    libraryGetAll         : [10,000    ] [0.0000360173225] [27,764.41805]
    libraryReplace        : [10,000    ] [0.0000367946148] [27,177.89018]
    libraryReplaceCallback: [10,000    ] [0.0000537220478] [18,614.33137]
    libraryGrep           : [10,000    ] [0.0000340729952] [29,348.75536]
    libraryFilter         : [10,000    ] [0.0000365005255] [27,396.86585]
    librarySplit          : [10,000    ] [0.0000345862627] [28,913.21357]

    nativeMatch           : [10,000    ] [0.0000058207989] [171,797.72428]
    nativeGet             : [10,000    ] [0.0000064183235] [155,803.92565]
    nativeGetAll          : [10,000    ] [0.0000080312252] [124,514.00310]
    nativeReplace         : [10,000    ] [0.0000087450981] [114,349.77481]
    nativeReplaceCallback : [10,000    ] [0.0000220663786] [ 45,317.81215]
    nativeGrep            : [10,000    ] [0.0000065322161] [153,087.40387]
    nativeFilter          : [10,000    ] [0.0000103927851] [ 96,220.59852]
    nativeSplit           : [10,000    ] [0.0000072141409] [138,616.64403]

    stringMatch           : [10,000    ] [0.0000032505751] [307,637.87324]
    stringGet             : [10,000    ] [0.0000055107594] [181,463.19515]
    stringGetAll          : [10,000    ] [0.0000081443071] [122,785.15327]
    stringReplace         : [10,000    ] [0.0000044827461] [223,077.54494]
    stringReplaceCallback : [10,000    ] [0.0000128215075] [ 77,993.94912]
    stringGrep            : [10,000    ] [0.0000074849844] [133,600.81289]
    stringFilter          : [10,000    ] [0.0000091922045] [108,787.83242]
    stringSplit           : [10,000    ] [0.0000038187742] [261,864.13271]
```

You can run the benchmark yourself

```shell
$ cd project_root
$ composer install
$ php vendor/athletic/athletic/bin/athletic -p tests/Gobie/Bench -b tests/bootstrap.php
```

Contribute
----------

Contributions are always welcome as well as any questions or issues.

Unit and integration testing is done via `phpunit` with configuration file `tests/complete.phpunit.xml`.
