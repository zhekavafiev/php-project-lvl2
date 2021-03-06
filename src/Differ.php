<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\AstBuilder\buildAst;
use function Differ\Formatters\render;

function genDiff($path1, $path2, $format = 'pretty')
{
    $normalizedPath1 = realpath($path1);
    $rawData1 = file_get_contents($normalizedPath1);
    $dataType1 = getTypeFile($path1);
    $parsedData1 = parse($rawData1, $dataType1);
    
    $normalizedPath2 = realpath($path2);
    $rawData2 = file_get_contents($normalizedPath2);
    $dataType2 = getTypeFile($path2);
    $parsedData2 = parse($rawData2, $dataType2);

    $internalTree = buildAst($parsedData1, $parsedData2);
    return render($internalTree, $format);
}

function getTypeFile($path)
{
    $fileInfo = pathinfo($path);
    return $fileInfo['extension'];
}
