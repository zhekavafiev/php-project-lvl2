<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    public function testDiffNestedJson()
    {
        $expected = file_get_contents("tests/fixtures/datafortestnested");
        $actual = genDiff("tests/fixtures/BeforeNestedJson.json", "tests/fixtures/afternested.json");
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffNestedYml()
    {
        $expected = file_get_contents("tests/fixtures/datafortestnested");
        $actual = genDiff("tests/fixtures/BeforeNestedYml.yml", "tests/fixtures/AfterNestedYml.yml");
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffPlain()
    {
        $expected = file_get_contents("tests/fixtures/datafortestplain");
        $actual = genDiff("tests/fixtures/BeforeNestedJson.json", "tests/fixtures/afternested.json", 'plain');
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffJson()
    {
        $expected = file_get_contents("tests/fixtures/datafortestjson");
        $actual = genDiff("tests/fixtures/BeforeNestedJson.json", "tests/fixtures/afternested.json", 'json');
        
        $this->assertEquals($expected, $actual);
    }
}
