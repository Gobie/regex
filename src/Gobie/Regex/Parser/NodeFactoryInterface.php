<?php

namespace Gobie\Regex\Parser;

interface NodeFactoryInterface
{
    public function createPosition($char);

    public function createChar($char);

    public function createSet($not = false, $tokens = array());

    public function createDot();

    public function createGroup($remember = true);

    public function createRoot();

    public function createTokenArray($tokens = array());

    public function createRepetition($from, $to);
}