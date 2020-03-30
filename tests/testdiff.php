<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Parse\parse;
use function Differ\Parse\printResultFindDiff;
use function Differ\Differ\processingDataFromFiles;


class DiffTest extends TestCase
{
    public function testDiff()
    {
        $expected1 = "{\n\thost:hexlet.io,\n\t-timeout:50,\n\t+timeout:20,\n\t-proxy:123.234.53.22,\n\t+verbose:true\n}"
         . PHP_EOL;
         $expected2 = "{\n\thost:hexlet.io,\n\t-timeout:50,\n\t+timeout:200,\n\t-proxy:123.234.53.22,\n\t+verbose:true\n}"
         . PHP_EOL;

        $pathToFiles1 = [
            'pathsbefore' => "tests/fixtures/beforeflat.json",
            'pathsafter' => "tests/fixtures/afterflat.json"
        ];
        $pathToFiles2 = [
            'pathsbefore' => "tests/fixtures/beforeflat.yml",
            'pathsafter' => "tests/fixtures/afterflat.yml"
        ];

        $this->assertEquals($expected1, printResultFindDiff(processingDataFromFiles(parse($pathToFiles1))));
        $this->assertEquals($expected2, printResultFindDiff(processingDataFromFiles(parse($pathToFiles2))));

    }
}
