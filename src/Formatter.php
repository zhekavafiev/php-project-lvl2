<?php

namespace Differ\Formatter;

function render($tree, $format)
{
    switch ($format) {
        case 'plain':
            return \Differ\Formatters\Plain\render($tree);
        case 'json':
            return \Differ\Formatters\Json\render($tree);
        case '':
            return \Differ\Formatters\Pretty\render($tree);
        case 'pretty':
            return \Differ\Formatters\Pretty\render($tree);
        default:
            echo "Error. File's format not supported." . PHP_EOL;
            break;
    }
}
