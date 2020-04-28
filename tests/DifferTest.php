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

    public function testDiffNested($path1, $path2, $expected)
    {
        $this->assertSame($expected, genDiff(normalizePath(__DIR__ . $path1), normalizePath(__DIR__ . $path2)));
    }

    public function checkRenderWithBraceProvider()
    {
        $pathForTestData = '/fixtures/datafortestnested';
        $expected = file_get_contents(normalizePath(__DIR__ . $pathForTestData));

        $pathBeforeFile1 = '/fixtures/BeforeNestedJson.json';
        $pathAfterFile1 = '/fixtures/afternested.json';

        $pathBeforeFile2 = '/fixtures/BeforeNestedYml.yml';
        $pathAfterFile2 = '/fixtures/AfterNestedYml.yml';


        return [
            [$pathBeforeFile1, $pathAfterFile1, $expected],
            [$pathBeforeFile2, $pathAfterFile2, $expected],
            [$pathBeforeFile1, $pathAfterFile2, $expected],
        ];
    }

    // public function testDiffNestedJson()
    // {
    //     $pathForTestData = '/fixtures/datafortestnested';
    //     $pathBeforeFile = '/fixtures/BeforeNestedJson.json';
    //     $pathAfterFile = '/fixtures/afternested.json';

    //     $expected = file_get_contents(normalizePath(__DIR__ . $pathForTestData));
    //     $actual = genDiff(normalizePath(__DIR__ . $pathBeforeFile), normalizePath(__DIR__ . $pathAfterFile));
        
    //     $this->assertEquals($expected, $actual);
    // }

    // public function testDiffNestedYml()
    // {
    //     $pathForTestData = '/fixtures/datafortestnested';
    //     $pathBeforeFile = '/fixtures/BeforeNestedYml.yml';
    //     $pathAfterFile = '/fixtures/AfterNestedYml.yml';

    //     $expected = file_get_contents(normalizePath(__DIR__ . $pathForTestData));
    //     $actual = genDiff(normalizePath(__DIR__ . $pathBeforeFile), normalizePath(__DIR__ . $pathAfterFile));
        
    //     $this->assertEquals($expected, $actual);
    // }

    public function testDiffPlain()
    {
        $pathForTestData = '/fixtures/datafortestplain';
        $pathBeforeFile = '/fixtures/BeforeNestedJson.json';
        $pathAfterFile = '/fixtures/afternested.json';

        $expected = file_get_contents(normalizePath(__DIR__ . $pathForTestData));
        $actual = genDiff(normalizePath(__DIR__ . $pathBeforeFile), normalizePath(__DIR__ . $pathAfterFile), 'plain');
        
        $this->assertEquals($expected, $actual);
    }

    public function testDiffJson()
    {
        $pathForTestData = '/fixtures/datafortestjson';
        $pathBeforeFile = '/fixtures/BeforeNestedJson.json';
        $pathAfterFile = '/fixtures/afternested.json';
        
        $expected = file_get_contents(normalizePath(__DIR__ . $pathForTestData));
        $actual = genDiff(normalizePath(__DIR__ . $pathBeforeFile), normalizePath(__DIR__ . $pathAfterFile), 'json');
        
        $this->assertEquals($expected, $actual);
    }
}
