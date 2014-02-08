<?php

namespace Gobie\Test\Regex\Wrappers\Pcre;

use Gobie\Regex\Wrappers\Pcre\PcreRegex;
use Gobie\Regex\Wrappers\RegexException;

class PcreRegexTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldShowPregMatchCompilationErrorDoesNotClearPregLastError()
    {
        try {
            // Runtime error
            PcreRegex::match('//u', "\xc3\x28");
        } catch (RegexException $e) {
            $this->assertSame('Malformed UTF-8 data; pattern: //u', $e->getShortMessage());
            $this->assertSame(\PREG_BAD_UTF8_ERROR, \preg_last_error());

            try {
                // Compilation error
                PcreRegex::match('/(/', '');
            } catch (RegexException $e) {
                $this->assertSame('Compilation failed: missing ) at offset 1; pattern: /(/', $e->getShortMessage());
                // Preg_last_error isn't cleared when compilation error occurs
                $this->assertSame(\PREG_BAD_UTF8_ERROR, \preg_last_error());

                return;
            }
        }

        $this->fail('Runtime and compilation errors should have occurred');
    }
}
