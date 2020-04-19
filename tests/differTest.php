<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    public function testDiffFlat()
    {
        $expected = file_get_contents("tests/fixtures/datafortestflat");

        $actual1 = genDiff("tests/fixtures/beforeflat.json", "tests/fixtures/afterflat.json");
        $actual2 = genDiff("tests/fixtures/beforeflat.yml", "tests/fixtures/afterflat.yml");
        
        $this->assertEquals($expected, $actual1);
        $this->assertEquals($expected, $actual2);
    }

    public function testDiffNested()
    {
        $expected = file_get_contents("tests/fixtures/datafortestnested");

        $actual = genDiff("tests/fixtures/beforenested.json", "tests/fixtures/afternested.json");
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffPlain()
    {

        $expected = file_get_contents("tests/fixtures/datafortestplain");
        $actual = genDiff("tests/fixtures/beforenested.json", "tests/fixtures/afternested.json", 'plain');
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffJson()
    {

        $expected = file_get_contents("tests/fixtures/datafortestjson");
        $actual = genDiff("tests/fixtures/beforenested.json", "tests/fixtures/afternested.json", 'json');
        
        $this->assertEquals($expected, $actual);
    }
}
