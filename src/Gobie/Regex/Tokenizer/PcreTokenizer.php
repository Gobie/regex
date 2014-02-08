<?php

namespace Gobie\Regex\Tokenizer;

use Gobie\Regex\Wrappers\Pcre\PcreRegex;

class PcreTokenizer implements TokenizerInterface
{

    public function tokenize($regex)
    {
        if (!\is_string($regex) || $regex === "") {
            throw new RegexTokenizerException('Invalid or empty regex "' . $regex . '"');
        }

        $len   = \mb_strlen($regex);
        $pos   = 0;
        $state = TokenizerInterface::REGEXP;

        $root       = new TokenNode(array(
            'type'       => 'root',
            'delimiters' => array(),
            'modifiers'  => array(),
            'stack'      => new TokenStack()
        ));
        $lastGroup  = $root;
        $last       = $root->stack;
        $groupStack = new TokenStack();

        $delimiter = null;
        $escaping  = false;

        while ($pos < $len) {
            $char = \mb_substr($regex, $pos, 1);

            switch ($state) {
                case TokenizerInterface::REGEXP:
                    if (PcreRegex::match('/\s|\\\\|[[:alnum:]]/', $char)) {
                        throw new RegexTokenizerException('Invalid delimiter "' . $char . '"', $pos);
                    } elseif ($char === '{') {
                        $delimiter = '}';
                    } else {
                        $delimiter = $char;
                    }

                    array_push($root->delimiters, $char, $delimiter);
                    $state = TokenizerInterface::PATTERN;
                    break;

                case TokenizerInterface::PATTERN:
                    if ($escaping) {
                        $escaping = false;
                        $last[]   = new TokenNode(array(
                            'type'  => 'char',
                            'value' => $char
                        ));
                        break;
                    }

                    switch ($char) {
                        case '\\':
                            $escaping = true;
                            break;

                        case '(':
                            $group        = new TokenNode(array(
                                'type'     => 'group',
                                'remember' => true,
                                'stack'    => new TokenStack(),
                            ));
                            $last[]       = $group;
                            $groupStack[] = $lastGroup;
                            $lastGroup    = $group;
                            $last         = $group->stack;
                            unset($group);
                            break;

                        case ')':
                            if (count($groupStack) === 0) {
                                throw new RegexTokenizerException('Unmatched )', $pos);
                            }

                            $lastGroup = $groupStack->pop();

                            $last = isset($lastGroup->options)
                                ? $lastGroup->options->last()
                                : $lastGroup->stack;
                            break;

                        case '|':
                            if (!isset($lastGroup->options)) {
                                $lastGroup->options = new TokenStack(array($lastGroup->stack));
                                unset($lastGroup->stack);
                            }

                            $stack                = new TokenStack();
                            $lastGroup->options[] = $stack;
                            $last                 = $stack;
                            unset($stack);
                            break;

                        case '^':
                        case '$':
                            $last[] = new TokenNode(array(
                                'type'  => 'position',
                                'value' => $char
                            ));
                            break;

                        case $delimiter:
                            if (count($groupStack)) {
                                throw new RegexTokenizerException('Unterminated group', $pos);
                            }

                            $state = TokenizerInterface::MODIFIERS;
                            break;

                        default:
                            $last[] = new TokenNode(array(
                                'type'  => 'char',
                                'value' => $char
                            ));
                            break;
                    }
                    break;

                case TokenizerInterface::MODIFIERS:
                    if (!PcreRegex::match('/[eimsuxADJSUX]/', $char)) {
                        throw new RegexTokenizerException('Unknown modifier "' . $char . '"', $pos);
                    }

                    $root->modifiers[] = $char;
                    break;
            }

            ++$pos;
        };

        if ($state !== TokenizerInterface::MODIFIERS) {
            throw new RegexTokenizerException('Missing delimiter "' . $delimiter . '"', $pos);
        }

        return $root;
    }
}
