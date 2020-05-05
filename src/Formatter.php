<?php

namespace Differ\Formatters;

function render($tree, $format)
{
    switch ($format) {
        case 'plain':
            return Plain\render($tree);
        case 'json':
            return Json\render($tree);
        case 'pretty':
            return Pretty\render($tree);
        default:
            throw new \Exception("{$format} - not supporeted format");
    }
}
