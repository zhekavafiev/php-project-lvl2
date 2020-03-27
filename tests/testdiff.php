<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class DiffTest extends TestCase
{
    public function testDiff()
    {
        $expected = "{\n\t-timeout: 20,\n\t+timeout: 50,\n\t-verbose: 1,\n\thost: hexlet.io,\n\t+proxy: 123.234.53.22\n}"
         . PHP_EOL;
        /*$pathToFiles1 = [
            "/home/evg/project1/php-project-lvl2/tests/fixtures/before.json",
            "/home/evg/project1/php-project-lvl2/tests/fixtures/after.json"
        ];*/
        $pathToFiles2 = [
            "tests/fixtures/before.json",
            "tests/fixtures/after.json"
        ];

        //$this->assertEquals($expected, genDiff($pathToFiles1));
        $this->assertEquals($expected, genDiff($pathToFiles2));
    }
}
