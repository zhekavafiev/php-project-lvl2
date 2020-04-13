<?php

namespace Differ\Differ;

use function Differ\Parse\getDataFromFiles;
use function Differ\ProcessingTree\buildingAsd;
use function Differ\Formatters\ToPlain\renderToPlain;
use function Differ\Formatters\ToJson\renderToJson;
use function Differ\Formatters\ToStringWithBrace\renderIToStringWithBrace;

function genDiff($path1, $path2, $format = '')
{
    $treeForProcessing = getDataFromFiles($path1, $path2);
    $asd = buildingAsd($treeForProcessing);
    // print_r($asd);
    if ($format == 'plain') {
        $result = renderToPlain($asd);
    } elseif ($format == 'json') {
        $result = renderToJson($asd);
    } else {
        $result = renderIToStringWithBrace($asd);
    }
    return $result;
}
