<?php

namespace Differ\Parser;

use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

function parse($path, $format)
{
    switch ($format) {
        case 'json':
            return json_decode($path, true);
        case 'yml':
            return Yaml::parse($path);
        case 'yaml':
            return Yaml::parse($path);
    }
}
