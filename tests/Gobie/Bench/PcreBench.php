<?php

namespace Gobie\Bench;

use Athletic\AthleticEvent;
use Gobie\Regex\Drivers\Pcre\PcreRegex;

class PcreBench extends AthleticEvent
{
    private $subject = 'Hello World';

    private $subjectArray = array('Hello', 'World');

    private $replacement = '-';

    private $pattern = '/l/';

    private $stringPattern = 'l';

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryMatch()
    {
        return PcreRegex::match($this->pattern, $this->subject);
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryGet()
    {
        $matches = PcreRegex::get($this->pattern, $this->subject);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryGetAll()
    {
        $matches = PcreRegex::getAll($this->pattern, $this->subject);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryReplace()
    {
        $res = PcreRegex::replace($this->pattern, $this->replacement, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryReplaceCallback()
    {
        $replacement = $this->replacement;
        $res         = PcreRegex::replace($this->pattern, function () use ($replacement) {
            return $replacement;
        }, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryGrep()
    {
        $res = PcreRegex::grep($this->pattern, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryFilter()
    {
        $res = PcreRegex::filter($this->pattern, $this->replacement, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function librarySplit()
    {
        $res = PcreRegex::split($this->pattern, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeMatch()
    {
        return \preg_match($this->pattern, $this->subject);
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeGet()
    {
        \preg_match($this->pattern, $this->subject, $matches);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeGetAll()
    {
        \preg_match_all($this->pattern, $this->subject, $matches);

        return $matches;
    }


    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeReplace()
    {
        $res = \preg_replace($this->pattern, $this->replacement, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeReplaceCallback()
    {
        $replacement = $this->replacement;
        $res         = \preg_replace_callback($this->pattern, function () use ($replacement) {
            return $replacement;
        }, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeGrep()
    {
        $res = \preg_grep($this->pattern, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeFilter()
    {
        $res = \preg_filter($this->pattern, $this->replacement, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeSplit()
    {
        $res = \preg_split($this->pattern, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringMatch()
    {
        return \strpos($this->subject, $this->stringPattern) !== false;
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringGet()
    {
        $out           = array();
        $stringPattern = $this->stringPattern;

        if (\strpos($this->subject, $stringPattern) !== false) {
            $out[] = $stringPattern;
        }

        return $out;
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringGetAll()
    {
        $stringPattern = $this->stringPattern;

        $count = \substr_count($this->subject, $stringPattern);

        return \array_fill(0, $count, $stringPattern);
    }


    /**
     * @iterations 10000
     * @group      string
     */
    public function stringReplace()
    {
        return \str_replace($this->stringPattern, $this->replacement, $this->subject);
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringReplaceCallback()
    {
        $replacement = $this->replacement;

        return \str_replace('World', \call_user_func(function () use ($replacement) {
            return $replacement;
        }), $this->subject);
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringGrep()
    {
        $stringPattern = $this->stringPattern;
        $out           = array();

        foreach ($this->subjectArray as $item) {
            if (\strpos($item, $stringPattern) !== false) {
                $out[] = $item;
            }
        }

        return $out;
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringFilter()
    {
        $stringPattern = $this->stringPattern;
        $replacement   = $this->replacement;
        $out           = array();

        foreach ($this->subjectArray as $item) {
            if (\strpos($item, $stringPattern) !== false) {
                $out[] = \str_replace($stringPattern, $replacement, $item);
            }
        }

        return $out;
    }

    /**
     * @iterations 10000
     * @group      string
     */
    public function stringSplit()
    {
        return explode($this->stringPattern, $this->subject);
    }
}
