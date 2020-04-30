<?php

namespace Differ\Differ;

use SplFileInfo;

use function Differ\Parser\parse;
use function Differ\Hundler\buildingAst;
use function Differ\Render\render;

function genDiff($path1, $path2, $format = '')
{
    $firstFileNormalizedPath = normalizePath($path1);
    $firstFileData = file_get_contents($firstFileNormalizedPath);
    $firstFileFormat = getFormatFile($path1);
    $parsedFirstFileData = parse($firstFileData, $firstFileFormat);
    
    $secondFileNormalizedPath = normalizePath($path2);
    $secondFileData = file_get_contents($secondFileNormalizedPath);
    $secondFileFormat = getFormatFile($path2);
    $parsedSecondFileData = parse($secondFileData, $secondFileFormat);

    $internalTree = buildingAst($parsedFirstFileData, $parsedSecondFileData);
    return render($internalTree, $format);
}

function normalizePath($path)
{
    return realpath($path);
}

function getFormatFile($path)
{
    $fileInfo = pathinfo($path);
    return $fileInfo['extension'];
}
