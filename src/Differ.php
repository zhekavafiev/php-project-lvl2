<?php

namespace Differ\Differ;

use SplFileInfo;

use function Differ\Parser\parse;
use function Differ\Hundler\buildingAst;
use function Differ\Render\render;

function genDiff($path1, $path2, $format = '')
{
    $firstFileNormalizedPath = normalizePath($path1);
    $firstFileData = readFileByPath($firstFileNormalizedPath);
    $firstFileFormat = getFormatFile($path1);
    $parsedFirstFileData = parse($firstFileData, $firstFileFormat);
    
    $secondFileNormalizedPath = normalizePath($path2);
    $secondFileData = readFileByPath($secondFileNormalizedPath);
    $secondFileFormat = getFormatFile($path2);
    $parsedSecondFileData = parse($secondFileData, $secondFileFormat);

    $internalTree = buildingAst($parsedFirstFileData, $parsedSecondFileData);
    return render($internalTree, $format);
}

function normalizePath($path)
{
    $absolutePath = realpath($path);
    $arrayPath = explode('/', $absolutePath);
    $normalizedPath = implode(DIRECTORY_SEPARATOR, $arrayPath);
    return $normalizedPath;
}

function readFileByPath($normalizedPath)
{
    return file_get_contents($normalizedPath);
}

function getFormatFile($path)
{
    $fileInfo = new SplFileInfo($path);
    return $fileInfo->getExtension();
}
