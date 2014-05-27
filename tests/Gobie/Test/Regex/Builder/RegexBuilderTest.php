<?php

namespace Gobie\Test\Regex\Builder;

class RegexBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideRegexDefinitions
     */
    public function testShouldBuildRegexLikeDefinition($regex, $definitions, $definitionName)
    {
        $builder = new RegexBuilder();
        foreach ($definitions as $name => $value) {
            call_user_func_array(array($builder, 'define'), array($name, $value));
        }
        $this->assertSame($regex, $builder->getRegex($definitionName));
    }

    public function provideRegexDefinitions()
    {
        return array(
            'ipv4'        => array(
                '{(2([0-4]\d|5[0-5])|1\d\d|[1-9]?\d)(\.(2([0-4]\d|5[0-5])|1\d\d|[1-9]?\d)){3}}',
                array(
                    'ipv4_seg'  => '2([0-4]\d|5[0-5])|1\d\d|[1-9]?\d',
                    'ipv4_addr' => '&ipv4_seg(\.&ipv4_seg){3}'
                ),
                'ipv4_addr'
            ),
            'us sentence' => array(
                '{(([A-Z])?([a-z])*)((\s)(([A-Z])?([a-z])*))*(\.)}',
                array(
                    'space'         => '\s',
                    'lower_us_char' => '[a-z]',
                    'upper_us_char' => '[A-Z]',
                    'dot'           => '\.',
                    'word'          => '&upper_us_char?&lower_us_char*',
                    'sentence'      => '&word(&space&word)*&dot'
                ),
                'sentence'
            ),
            'uid'         => array(
                '{(([\dA-F]){4})(-(([\dA-F]){4})){3}}',
                array(
                    'hex'      => '[\dA-F]',
                    'uid_part' => '&hex{4}',
                    'uid'      => '&key_part(-&key_part){3}',
                ),
                'uid'
            ),
        );
    }
}
