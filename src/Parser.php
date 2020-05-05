<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($path, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($path);
        case 'yml':
        case 'yaml':
            return Yaml::parse($path, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("{$format} not supported");
    }
}
