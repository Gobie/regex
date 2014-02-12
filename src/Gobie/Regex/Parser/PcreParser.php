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

        $root      = $this->nodeFactory->createRoot();
        $lastGroup = $root;
        $last      = $root->stack;
        /** @var $groupStack NodeArray */
        $groupStack = $this->nodeFactory->createTokenArray();

        $pos       = 0;
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
                        case $delimiter:
                            if ($unterminatedGroups = count($groupStack)) {
                                throw new ParseException($unterminatedGroups . ' unterminated group(s)', $pos);
                            }

                            $state = ParserInterface::MODIFIERS;
                            break;

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

                            $lastGroup->options[] = $last = $this->nodeFactory->createTokenArray();
                            break;

                        case '[':
                            $classRegex =
                                '/^(?:[a-z0-9]-[a-z0-9]|\\\\.|[^\\]\\[\\' . $delimiter . '])/is';
                            $tokens     = array();
                            while ($token = $tokenizer->pop($classRegex)) {
                                $tokens[] = $this->nodeFactory->createChar($token);
                            }
                            $last[] = $this->nodeFactory->createSet($tokens);

                            $token = $tokenizer->pop('/^\\]/');
                            $pos   = $tokenizer->key();

                            if (!$token) {
                                throw new ParseException('Unterminated [', $pos);
                            }

                            unset($tokens, $token);
                            break;

                        case ']':
                            throw new ParseException('Unmatched ]', $pos);

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
