<?php

namespace Gobie\Bench;

use Athletic\AthleticEvent;
use Gobie\Regex\Wrappers\Mb\MbRegex;

class MbBench extends AthleticEvent
{
    private $subject = 'Hello World';

    private $subjectArray = array('Hello', 'World');

    private $replacement = '-';

    private $pattern = 'l';

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryMatch()
    {
        return MbRegex::match($this->pattern, $this->subject);
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryGet()
    {
        $matches = MbRegex::get($this->pattern, $this->subject);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryGetAll()
    {
        $matches = MbRegex::getAll($this->pattern, $this->subject);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryReplace()
    {
        $res = MbRegex::replace($this->pattern, $this->replacement, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryReplaceCallback()
    {
        $replacement = $this->replacement;
        $res         = MbRegex::replace($this->pattern, function () use ($replacement) {
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
        $res = MbRegex::grep($this->pattern, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function libraryFilter()
    {
        $res = MbRegex::filter($this->pattern, $this->replacement, $this->subjectArray);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      library
     */
    public function librarySplit()
    {
        $res = MbRegex::split($this->pattern, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeMatch()
    {
        return \mb_ereg_match($this->pattern, $this->subject);
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeGet()
    {
        \mb_ereg($this->pattern, $this->subject, $matches);

        return $matches;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeReplace()
    {
        $res = \mb_ereg_replace($this->pattern, $this->replacement, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeReplaceCallback()
    {
        $replacement = $this->replacement;
        $res         = \mb_ereg_replace_callback($this->pattern, function () use ($replacement) {
            return $replacement;
        }, $this->subject);

        return $res;
    }

    /**
     * @iterations 10000
     * @group      native
     */
    public function nativeSplit()
    {
        $res = \mb_split($this->pattern, $this->subject);

        return $res;
    }
}
