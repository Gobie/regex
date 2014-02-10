<?php

namespace Gobie\Regex\Tokenizer;

use Gobie\Regex\Wrappers\Pcre\PcreRegex;

class PcreParser implements ParserInterface
{

    /**
     * @var TokenFactoryInterface
     */
    private $tokenFactory;

    public function __construct(TokenFactoryInterface $tokenFactory)
    {
        $this->tokenFactory = $tokenFactory;
    }

    public function parse($regex)
    {
        if (!\is_string($regex) || $regex === "") {
            throw new ParseException('Invalid or empty regex "' . $regex . '"');
        }

        $len   = \mb_strlen($regex);
        $pos   = 0;
        $state = ParserInterface::REGEXP;

        $root       = $this->tokenFactory->createRoot();
        $lastGroup  = $root;
        $last       = $root->stack;
        $groupStack = $this->tokenFactory->createTokenArray();

        $delimiter = null;
        $escaping  = false;

        while ($pos < $len) {
            $char = \mb_substr($regex, $pos, 1);

            switch ($state) {
                case ParserInterface::REGEXP:
                    if (PcreRegex::match('/\s|\\\\|[[:alnum:]]/', $char)) {
                        throw new ParseException('Invalid delimiter "' . $char . '"', $pos);
                    } elseif ($char === '{') {
                        $delimiter = '}';
                    } else {
                        $delimiter = $char;
                    }

                    $root->delimiters = array($char, $delimiter);
                    $state            = ParserInterface::PATTERN;
                    break;

                case ParserInterface::PATTERN:
                    if ($escaping) {
                        $escaping = false;
                        $last[]   = $this->tokenFactory->createChar($char);
                        break;
                    }

                    switch ($char) {
                        case '\\':
                            $escaping = true;
                            break;

                        case '(':
                            $groupStack[] = $lastGroup;
                            $group        = $this->tokenFactory->createGroup();
                            $last[]       = $group;
                            $lastGroup    = $group;
                            $last         = $group->stack;
                            unset($group);
                            break;

                        case ')':
                            if (count($groupStack) === 0) {
                                throw new ParseException('Unmatched )', $pos);
                            }

                            $lastGroup = $groupStack->pop();

                            $last = isset($lastGroup->options)
                                ? $lastGroup->options->last()
                                : $lastGroup->stack;
                            break;

                        case '|':
                            if (!isset($lastGroup->options)) {
                                $lastGroup->options = $this->tokenFactory->createTokenArray(array($lastGroup->stack));
                                unset($lastGroup->stack);
                            }

                            $stack                = $this->tokenFactory->createTokenArray();
                            $lastGroup->options[] = $stack;
                            $last                 = $stack;
                            unset($stack);
                            break;

                        case '^':
                        case '$':
                            $last[] = $this->tokenFactory->createPosition($char);
                            break;

                        case '.':
                            $last[] = $this->tokenFactory->createDot();
                            break;

                        case $delimiter:
                            if (count($groupStack)) {
                                throw new ParseException('Unterminated group', $pos);
                            }

                            $state = ParserInterface::MODIFIERS;
                            break;

                        default:
                            $last[] = $this->tokenFactory->createChar($char);
                            break;
                    }
                    break;

                case ParserInterface::MODIFIERS:
                    if (!PcreRegex::match('/[eimsuxADJSUX]/', $char)) {
                        throw new ParseException('Unknown modifier "' . $char . '"', $pos);
                    }

                    $root->modifiers[] = $char;
                    break;
            }

            ++$pos;
        };

        if ($state !== ParserInterface::MODIFIERS) {
            throw new ParseException('Missing delimiter "' . $delimiter . '"', $pos);
        }

        return $root;
    }
}
