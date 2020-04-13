<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

// use function Differ\Parse\parse;
use function Differ\Differ\genDiff;


class DiffTest extends TestCase
{
    public function testDiff()
    {
        $expected1 = file_get_contents("tests/fixtures/datafortestflat");
        $expected2 = file_get_contents("tests/fixtures/datafortestflat");
        $expected3 = file_get_contents("tests/fixtures/datafortestnested");
        $expected4 = file_get_contents("tests/fixtures/datafortestplain");


        $actual1 = genDiff("tests/fixtures/beforeflat.json", "tests/fixtures/afterflat.json");
        $actual2 = genDiff("tests/fixtures/beforeflat.yml", "tests/fixtures/afterflat.yml");
        $actual3 = genDiff("tests/fixtures/beforenested.json", "tests/fixtures/afternested.json");
        $actual4 = genDiff("tests/fixtures/beforenested.json", "tests/fixtures/afternested.json", 'plain');
        
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertEquals($expected3, $actual3);
        $this->assertEquals($expected4, $actual4);

    }
}
