<?php
namespace Gobie\Test\Regex\Tokenizer;

use Gobie\Regex\Tokenizer\PcreTokenizer;
use Gobie\Regex\Tokenizer\RegexTokenizer;
use Gobie\Regex\Tokenizer\RegexTokenizerException;

class RegexTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RegexTokenizer
     */
    private $tokenizer;

    protected function setUp()
    {
        parent::setUp();
        $this->tokenizer = new RegexTokenizer(new PcreTokenizer());
    }

    /**
     * @dataProvider provideCorrectRegex
     */
    public function testShouldTokenize($regex, $expectedTokens)
    {
        $token = $this->tokenizer->tokenize($regex);
        $this->assertSame($token->toArray(), $expectedTokens);
    }

    /**
     * @dataProvider provideIncorrectRegex
     */
    public function testShouldFailTokenize($regex, $expectedMessage)
    {
        try {
            $this->tokenizer->tokenize($regex);
            $this->fail('Exception should have been thrown');
        } catch (RegexTokenizerException $e) {
            $this->assertSame($e->getMessage(), $expectedMessage);
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
            '/a^a/'         => array(
                '/a^a/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'a'),
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
            '/b$b/'         => array(
                '/b$b/',
                array(
                    'type'       => 'root',
                    'delimiters' => array('/', '/'),
                    'modifiers'  => array(),
                    'stack'      => array(
                        array('type' => 'char', 'value' => 'b'),
                        array('type' => 'position', 'value' => '$'),
                        array('type' => 'char', 'value' => 'b'),
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
        );
    }

    public function provideIncorrectRegex()
    {
        return array(
            10    => array(10, 'Invalid or empty regex "10"'),
            ''    => array('', 'Invalid or empty regex ""'),
            '8//' => array('8//', 'Invalid delimiter "8" at position 0'),
            '//8' => array('//8', 'Unknown modifier "8" at position 2'),
            '{a'  => array('{a', 'Missing delimiter "}" at position 2'),
            '/(/' => array('/(/', 'Unterminated group at position 2'),
            '/)/' => array('/)/', 'Unmatched ) at position 1'),
        );
    }
}
