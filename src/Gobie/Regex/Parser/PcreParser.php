<?php

namespace Gobie\Regex\Parser;

use Gobie\Regex\Wrappers\Pcre\PcreRegex;

class PcreParser implements ParserInterface
{

    /**
     * @var TokenizerFactoryInterface
     */
    private $tokenizerFactory;

    /**
     * @var NodeFactoryInterface
     */
    private $nodeFactory;

    public function __construct(TokenizerFactoryInterface $tokenizerFactory, NodeFactoryInterface $nodeFactory)
    {
        $this->tokenizerFactory = $tokenizerFactory;
        $this->nodeFactory      = $nodeFactory;
    }

    public function parse($regex)
    {
        if (!\is_string($regex) || $regex === "") {
            throw new ParseException('Invalid or empty regex "' . $regex . '"');
        }

        $tokenizer = $this->tokenizerFactory->create($regex);
        $state     = ParserInterface::REGEXP;

        $root       = $this->nodeFactory->createRoot();
        $lastGroup  = $root;
        $last       = $root->stack;
        $groupStack = $this->nodeFactory->createTokenArray();

        $delimiter = null;

        foreach ($tokenizer as $pos => $char) {

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
                    switch ($char) {
                        case '\\':
                            $tokenizer->next();
                            if (!$tokenizer->valid()) {
                                throw new ParseException('Unfinished escape sequence', $pos);
                            }

                            $next = $tokenizer->current();
                            switch ($next) {
                                case 'n':
                                    $last[] = $this->nodeFactory->createChar("\n");
                                    break;

                                case 'r':
                                    $last[] = $this->nodeFactory->createChar("\r");
                                    break;

                                case 't':
                                    $last[] = $this->nodeFactory->createChar("\t");
                                    break;

                                default:
                                    $last[] = $this->nodeFactory->createChar($next);
                                    break;
                            }
                            break;

                        case '(':
                            $groupStack[] = $lastGroup;
                            $group        = $this->nodeFactory->createGroup();
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
                                $lastGroup->options = $this->nodeFactory->createTokenArray(array($lastGroup->stack));
                                unset($lastGroup->stack);
                            }

                            $stack                = $this->nodeFactory->createTokenArray();
                            $lastGroup->options[] = $stack;
                            $last                 = $stack;
                            unset($stack);
                            break;

                        case '^':
                        case '$':
                            $last[] = $this->nodeFactory->createPosition($char);
                            break;

                        case '.':
                            $last[] = $this->nodeFactory->createDot();
                            break;

                        case '+':
                            $last[] = $this->nodeFactory->createRepetition("1", "INF");
                            break;

                        case '*':
                            $last[] = $this->nodeFactory->createRepetition("0", "INF");
                            break;

                        case '?':
                            $last[] = $this->nodeFactory->createRepetition("0", "1");
                            break;

                        case $delimiter:
                            if (count($groupStack)) {
                                throw new ParseException('Unterminated group', $pos);
                            }

                            $state = ParserInterface::MODIFIERS;
                            break;

                        default:
                            $last[] = $this->nodeFactory->createChar($char);
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
        };

        if ($state !== ParserInterface::MODIFIERS) {
            throw new ParseException('Missing delimiter "' . $delimiter . '"', $pos);
        }

        return $root;
    }
}
