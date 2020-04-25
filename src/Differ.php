<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Hundler\buildingAst;
use function Differ\Render\render;

function genDiff($path1, $path2, $format = '')
{
    $firstFile = parse($path1);
    $secondFile = parse($path2);
    $internalTree = buildingAst($firstFile, $secondFile);
    return render($internalTree, $format);
}
