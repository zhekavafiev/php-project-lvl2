<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    /**
     * @dataProvider differFormatters
     */

    public function testDiff($fileName1, $fileName2, $format, $expectedFile)
    {
        $actual = genDiff(getFilePath($fileName1), getFilePath($fileName2), $format);
        $expected = trim(file_get_contents(getFilePath($expectedFile)));
        
        $this->assertSame($expected, $actual);
    }

    public function differFormatters()
    {
        return [
            'pretty_json' => ['before.json', 'after.json', 'pretty', 'test_pretty'],
            'pretty_yaml' => ['before.yml', 'after.yml', 'pretty', 'test_pretty'],
            'plain' => ['before.json', 'after.json', 'plain', 'test_plain'],
            'json' => ['before.json', 'after.json', 'json', 'test_json']
        ];
    }
}

function getFilePath($fileName)
{
    return __DIR__ . "/fixtures/{$fileName}";
}
