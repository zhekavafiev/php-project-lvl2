<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\normalizePath;
use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    /**
     * @dataProvider checkRenderWithBraceProvider
     */

    public function testDiffNested($fileName1, $fileName2, $format, $expected)
    {
        $this->assertSame($expected, genDiff(getNormalisedPath($fileName1), getNormalisedPath($fileName2), $format));
    }

    public function checkRenderWithBraceProvider()
    {
        $format1 = '';
        $format2 = 'plain';
        $format3 = 'json';

        $expectedFileName1 = 'StringTestNested';
        $expectedFileName2 = 'StringTestPlain';
        $expectedFileName3 = 'StringTestJson';

        $expected1 = file_get_contents(getNormalisedPath($expectedFileName1));
        $expected2 = file_get_contents(getNormalisedPath($expectedFileName2));
        $expected3 = file_get_contents(getNormalisedPath($expectedFileName3));

        $actualFirstFileName1 = 'BeforeNested.json';
        $actualSecondFileName1 = 'AfterNested.json';

        $actualFirstFileName2 = 'BeforeNested.yml';
        $actualSecondFileName2 = 'AfterNested.yml';

        return [
            [$actualFirstFileName1, $actualSecondFileName1, $format1, $expected1],
            [$actualFirstFileName2, $actualSecondFileName2, $format1, $expected1],
            [$actualFirstFileName1, $actualSecondFileName2, $format1, $expected1],
            [$actualFirstFileName1, $actualSecondFileName1, $format2, $expected2],
            [$actualFirstFileName1, $actualSecondFileName1, $format3, $expected3],
        ];
    }
}

function getNormalisedPath($fileName)
{
    $absolutePath = realpath(__DIR__) . "/fixtures";
    $arrayPath = explode('/', $absolutePath);
    $arrayPath[] = $fileName;
    $normalizedPath = implode(DIRECTORY_SEPARATOR, $arrayPath);
    return $normalizedPath;
}

// var_dump(getNormalisedPath('abc.abs'));
