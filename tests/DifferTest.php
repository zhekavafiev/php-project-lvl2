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
        $this->assertSame($expected, genDiff(getFilePath($fileName1), getFilePath($fileName2), $format));
    }

    public function checkRenderWithBraceProvider()
    {
        $format1 = 'pretty';
        $format2 = 'plain';
        $format3 = 'json';

        $expectedFileName1 = 'test_pretty';
        $expectedFileName2 = 'test_plain';
        $expectedFileName3 = 'test_json';

        $expected1 = file_get_contents(getFilePath($expectedFileName1));
        $expected2 = file_get_contents(getFilePath($expectedFileName2));
        $expected3 = file_get_contents(getFilePath($expectedFileName3));

        $actualFirstFileName1 = 'before_nested.json';
        $actualSecondFileName1 = 'after_nested.json';

        $actualFirstFileName2 = 'before_nested.yml';
        $actualSecondFileName2 = 'after_nested.yml';

        return [
            // [$actualFirstFileName1, $actualSecondFileName1, $format1, $expected1],
            // [$actualFirstFileName2, $actualSecondFileName2, $format1, $expected1],
            // [$actualFirstFileName1, $actualSecondFileName2, $format1, $expected1],
            [$actualFirstFileName1, $actualSecondFileName1, $format2, $expected2],
            // [$actualFirstFileName1, $actualSecondFileName1, $format3, $expected3],
        ];
    }
}

function getFilePath($fileName)
{
    $absolutePath = [__DIR__, "fixtures", $fileName];
    return implode(DIRECTORY_SEPARATOR, $absolutePath);
}
