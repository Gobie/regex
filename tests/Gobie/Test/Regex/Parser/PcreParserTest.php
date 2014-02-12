<?php
namespace Gobie\Test\Regex\Parser;

use Gobie\Regex\Parser\NodeFactory;
use Gobie\Regex\Parser\ParseException;
use Gobie\Regex\Parser\PcreParser;
use Gobie\Regex\Parser\RegexParser;
use Gobie\Regex\Parser\TokenizerFactory;

class PcreParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegexParser
     */
    private $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = new PcreParser(new TokenizerFactory(), new NodeFactory());
    }

    /**
     * @dataProvider provideCorrectRegex
     */
    public function testShouldTokenize($regex, $expectedTokens)
    {
        $token = $this->parser->parse($regex);
        $this->assertSame($expectedTokens, $token->toArray());
    }

    /**
     * @dataProvider provideIncorrectRegex
     */
    public function testShouldFailTokenize($regex, $expectedMessage)
    {
        try {
            $this->parser->parse($regex);
            $this->fail('Exception should have been thrown');
        } catch (ParseException $e) {
            $this->assertSame($expectedMessage, $e->getMessage());
        }
    }

    public function provideCorrectRegex()
    {
        return array(
            '//'            => array(
                '//',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(),
                )
            ),
            '//iS'          => array(
                '//iS',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array('i', 'S'),
                    'stack'      => array(),
                )
            ),
            '/a/'           => array(
                '/a/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
                    )
                )
            ),
            '{a}'           => array(
                '{a}',
                array(
                    'type'       => 'root',
                    'delimiters' => array('{', '}'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
                    )
                )
            ),
            '/a|b|c/'       => array(
                '/a|b|c/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'options'    => array(
                        array(
                            array('type' => 'char', 'value' => 'a'),
                        ),
                        array(
                            array('type' => 'char', 'value' => 'b'),
                        ),
                        array(
                            array('type' => 'char', 'value' => 'c'),
                        )
                    )
                )
            ),
            '/a\\|b/'       => array(
                '/a\\|b/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
                        array('type' => 'char', 'value' => '|'),
                        array('type' => 'char', 'value' => 'b'),
                    )
                )
            ),
            '/\\\\/'        => array(
                '/\\\\/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => '\\'),
                    )
                )
            ),
            '/^a/'          => array(
                '/^a/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'position', 'value' => '^'),
                        array('type' => 'char', 'value' => 'a'),
                    )
                )
            ),
            '/b$/'          => array(
                '/b$/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'b'),
                        array('type' => 'position', 'value' => '$'),
                    )
                )
            ),
            '/a^b$c/'       => array(
                '/a^b$c/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
                        array('type' => 'position', 'value' => '^'),
                        array('type' => 'char', 'value' => 'b'),
                        array('type' => 'position', 'value' => '$'),
                        array('type' => 'char', 'value' => 'c'),
                    )
                )
            ),
            '/(a|b)/'       => array(
                '/(a|b)/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array(
                            'type'     => 'group',
                            'remember' => true,
                            'options'  => array(
                                array(
                                    array('type' => 'char', 'value' => 'a'),
                                ),
                                array(
                                    array('type' => 'char', 'value' => 'b'),
                                )
                            )
                        )
                    )
                )
            ),
            '/(a|(b))|(c)/' => array(
                '/(a|(b))|(c)/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'options'    => array(
                        array(
                            array(
                                'type'     => 'group',
                                'remember' => true,
                                'options'  => array(
                                    array(
                                        array('type' => 'char', 'value' => 'a'),
                                    ),
                                    array(
                                        array(
                                            'type'     => 'group',
                                            'remember' => true,
                                            'stack'    => array(
                                                array('type' => 'char', 'value' => 'b'),
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        array(
                            array(
                                'type'     => 'group',
                                'remember' => true,
                                'stack'    => array(
                                    array('type' => 'char', 'value' => 'c'),
                                )
                            )
                        )
                    )
                )
            ),
            '/./'           => array(
                '/./',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array(
                            'type'  => 'set',
                            'not'   => true,
                            'stack' => array(
                                array('type' => 'char', 'value' => "\n")
                            )
                        ),
                    )
                )
            ),
            '/a+b*c?/'      => array(
                '/a+b*c?/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
                        array('type' => 'repetition', 'from' => '1', 'to' => 'INF'),
                        array('type' => 'char', 'value' => 'b'),
                        array('type' => 'repetition', 'from' => '0', 'to' => 'INF'),
                        array('type' => 'char', 'value' => 'c'),
                        array('type' => 'repetition', 'from' => '0', 'to' => '1'),
                    ),
                )
            ),
            '/\\n\\r\\t/'   => array(
                '/\\n\\r\\t/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => "\n"),
                        array('type' => 'char', 'value' => "\r"),
                        array('type' => 'char', 'value' => "\t"),
                    )
                )
            ),
        );
    }

    public function provideIncorrectRegex()
    {
        return array(
            10    => array(10, 'Invalid or empty regex "10"'),
            ''    => array('', 'Invalid or empty regex ""'),
            '8//' => array('8//', 'Invalid delimiter "8" at position 0'),
            '//8' => array('//8', 'Unknown modifier "8" at position 2'),
            '{a'  => array('{a', 'Missing delimiter "}" at position 1'),
            '/(/' => array('/(/', 'Unterminated group at position 2'),
            '/)/' => array('/)/', 'Unmatched ) at position 1'),
            '/\\' => array('/\\', 'Unfinished escape sequence at position 1'),
        );
    }
}
