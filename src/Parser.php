<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($path, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($path, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($path);
        default:
            throw new \Exception("{$format} not supported");
    }
}
