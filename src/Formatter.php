<?php

namespace Differ\Formatter;

use function Differ\Formatters\ToPlain\renderToPlain;
use function Differ\Formatters\ToJson\renderToJson;
use function Differ\Formatters\ToStringWithBrace\renderIToStringWithBrace;

function render($tree, $format)
{
    switch ($format) {
        case 'plain':
            return renderToPlain($tree);
        case 'json':
            return renderToJson($tree);
        case '':
            return renderIToStringWithBrace($tree);
        default:
            echo "Error. File's format not supported." . PHP_EOL;
            break;
    }
}
