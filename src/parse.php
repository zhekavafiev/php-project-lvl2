<?php

namespace Differ\Parse;

use Symfony\Component\Yaml\Yaml;

function parse(array $paths)
{
    $endSimbolsBefore = substr($paths['pathsbefore'], -3);
    $endSimbolsAfter = substr($paths['pathsafter'], -3);
    if ($endSimbolsBefore == 'yml' && $endSimbolsAfter == 'yml') {
        $type = 'yml';
        $pathToBefore = Yaml::parse(file_get_contents($paths['pathsbefore']));
        $pathToAfter = Yaml::parse(file_get_contents($paths['pathsafter']));
    } elseif ($endSimbolsBefore == 'son' && $endSimbolsAfter == 'son') {
        $type = 'json';
        $pathToBefore = json_decode(file_get_contents($paths['pathsbefore']), true);
        $pathToAfter = json_decode(file_get_contents($paths['pathsafter']), true);
    } else {
        echo "This files not supported or diffrents type" . PHP_EOL;
        return;
    }
    return [
        'type' => $type,
        'pathsbefore' => $pathToBefore,
        'pathsafter' => $pathToAfter
    ];
}

function printResultFindDiff(array $data)
{
    $result = '';
    if ($data['type'] == 'json') {
        $convertToString = json_encode($data['diffrents']);
    } elseif ($data['type'] == 'yml') {
        $convertToString = Yaml::dump($data['diffrents'], 0);
    }

    for ($i = 0; $i < strlen($convertToString); $i++) {
        if ($convertToString[$i] != "'" && $convertToString[$i] != "\"" && $convertToString[$i] != " ") {
            $result .= $convertToString[$i];
        }
    }

    $convertString = implode(",\n\t", explode(',', substr($result, 1, -1)));
    $rightString = "{\n\t{$convertString}\n}" . PHP_EOL;

    return $rightString;
}
