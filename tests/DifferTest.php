<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\normalizePath;
use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    /**
     * @dataProvider chechRenderPrettyFormat
     */

    public function testDiff($fileName1, $fileName2, $format, $expected)
    {
        $this->assertSame($expected, genDiff(getFilePath($fileName1), getFilePath($fileName2), $format));
    }

    public function chechRenderPrettyFormat()
    {
        $format1 = 'pretty';

        $expectedFileName1 = 'test_pretty';

        $expected1 = file_get_contents(getFilePath($expectedFileName1));

        $actualFirstFileName1 = 'before_nested.json';
        $actualSecondFileName1 = 'after_nested.json';

        $actualFirstFileName2 = 'before_nested.yml';
        $actualSecondFileName2 = 'after_nested.yml';

        return [
            [$actualFirstFileName1, $actualSecondFileName1, $format1, $expected1],
            [$actualFirstFileName2, $actualSecondFileName2, $format1, $expected1],
            [$actualFirstFileName1, $actualSecondFileName2, $format1, $expected1],
        ];
    }

    public function testDiffPlain()
    {
        $actualFirstFileName = 'before_nested.json';
        $actualSecondFileName = 'after_nested.json';
        $format = 'plain';

        $expectedFileName = 'test_plain';
        $expected = file_get_contents(getFilePath($expectedFileName));
        $actual = genDiff(getFilePath($actualFirstFileName), getFilePath($actualSecondFileName), $format);

        $this->assertEquals($expected, $actual);
    }

    public function testDiffJson()
    {
        $actualFirstFileName = 'before_nested.json';
        $actualSecondFileName = 'after_nested.json';
        $format = 'json';

        $expectedFileName = 'test_json';
        $expected = file_get_contents(getFilePath($expectedFileName));
        $actual = genDiff(getFilePath($actualFirstFileName), getFilePath($actualSecondFileName), $format);

        $this->assertEquals($expected, $actual);
    }

}

function getFilePath($fileName)
{
    $absolutePath = [__DIR__, "fixtures", $fileName];
    return implode(DIRECTORY_SEPARATOR, $absolutePath);
}
