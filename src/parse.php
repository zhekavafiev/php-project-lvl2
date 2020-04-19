<?php

namespace Differ\Parse;

use Symfony\Component\Yaml\Yaml;

function getDataFromFiles($path1, $path2)
{
    $endSimbolsBefore = substr($path1, -3);

    if ($endSimbolsBefore == 'yml') {
        $pathToBefore = Yaml::parse(file_get_contents($path1));
    } elseif ($endSimbolsBefore == 'son') {
        $pathToBefore = json_decode(file_get_contents($path1), true);
    } else {
        return "Files not supported";
    }

    $endSimbolsAfter = substr($path2, -3);

    if ($endSimbolsAfter == 'yml') {
        $pathToAfter = Yaml::parse(file_get_contents($path2));
    } elseif ($endSimbolsAfter == 'son') {
        $pathToAfter = json_decode(file_get_contents($path2), true);
    } else {
        return "Files not supported";
    }
    // var_dump(($pathToAfter));
    return [
        'pathsbefore' => $pathToBefore,
        'pathsafter' => $pathToAfter
    ];
}
