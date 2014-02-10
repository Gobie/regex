<?php

namespace Gobie\Test\Regex\Parser;

use Gobie\Regex\Parser\Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideIterationData
     */
    public function testShouldIterate($data, $expectedTokens)
    {
        $tokens    = array();
        $tokenizer = new Tokenizer($data);
        foreach ($tokenizer as $pos => $token) {
            $tokens[$pos] = $token;
        }
        $this->assertSame($expectedTokens, $tokens);
    }

    public function provideIterationData()
    {
        return array(
            array('abc', array('a', 'b', 'c')),
            array('ščř', array('š', 'č', 'ř')),
            array(
                json_decode('"\u220F\u2210\u2211"'),
                array(json_decode('"\u220F"'), json_decode('"\u2210"'), json_decode('"\u2211"'))
            ),
        );
    }

    /**
     * @dataProvider provideIncorrectTypeData
     */
    public function testShouldAcceptOnlyString($data, $expectedMessage)
    {
        try {
            new Tokenizer($data);
        } catch (\InvalidArgumentException $e) {
            $this->assertSame($expectedMessage, $e->getMessage());
        }
    }

    public function provideIncorrectTypeData()
    {
        return array(
            array(array(), 'Argument must be a string, but "array" was given'),
            array(new \stdClass(), 'Argument must be a string, but "object" was given'),
            array(123456, 'Argument must be a string, but "integer" was given'),
        );
    }
}
