<?php

namespace Differ\Parser;

use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

function parse($path)
{
    $fileInfo = new SplFileInfo($path);
    $extention = $fileInfo->getExtension();
    switch ($extention) {
        case 'json':
            return json_decode(file_get_contents($path), true);
            break;
        case 'yml':
            return Yaml::parse(file_get_contents($path));
            break;
    }
}
