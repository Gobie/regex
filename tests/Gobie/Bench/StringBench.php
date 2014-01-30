<?php

namespace Gobie\Bench;

use Athletic\AthleticEvent;

class StringBench extends AthleticEvent
{
    private $subject = 'Hello World';

    private $subjectArray = array('Hello', 'World');

    private $replacement = '-';

    private $stringPattern = 'l';

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
