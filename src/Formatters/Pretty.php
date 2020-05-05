<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    $resultStrint = "{\n";
    $getString = function ($node, &$resultStrint, $indentetion = " ") use (&$getString) {
        $getLine = array_reduce($node, function ($resultStrint, $el) use (&$getString, $indentetion) {
            $resultStrint .= $indentetion;

            if (array_key_exists('newValue', $el) && is_array($el['newValue'])) {
                $el['newValue'] = upgradeArrayValue($el['newValue'], $indentetion);
            }
            if (array_key_exists('oldValue', $el) && is_array($el['oldValue'])) {
                $el['oldValue'] = upgradeArrayValue($el['oldValue'], $indentetion);
            }


            if ($el['type'] == 'Added') {
                if ($el['oldValue'] === true) {
                    $resultStrint .= "$indentetion+ {$el['name']}: true\n";
                } else {
                    $resultStrint .= "$indentetion+ {$el['name']}: {$el['oldValue']}\n";
                }
            }

            if ($el['type'] == 'Removed') {
                if ($el['newValue'] === true) {
                    $resultStrint .= "$indentetion- {$el['name']}: true\n";
                } else {
                    $resultStrint .= "$indentetion- {$el['name']}: {$el['newValue']}\n";
                }
            }

            if ($el['type'] == 'Changed') {
                $resultStrint .= "$indentetion+ {$el['name']}: {$el['newValue']}\n";
                $resultStrint .= "$indentetion$indentetion- {$el['name']}: {$el['oldValue']}\n";
            }

            if ($el['type'] == 'Unchanged' && empty($el['children'])) {
                if ($el['newValue'] === true) {
                    $resultStrint .= "  $indentetion{$el['name']}: true\n";
                } else {
                    $resultStrint .= "  $indentetion{$el['name']}: {$el['newValue']}\n";
                }
            }

            if ($el['type'] == 'Nested') {
                $resultStrint .= "  $indentetion{$el['name']}: {\n";
                $newIndentetion = $indentetion . "  ";
                return "{$getString($el['children'], $resultStrint, $newIndentetion)}{$indentetion}   }\n";
            }
            return $resultStrint;
        }, $resultStrint);
        return $getLine;
    };

    return $getString($tree, $resultStrint) . "}\n";
}

function upgradeArrayValue($value, $indentetion = '  ')
{
    $resultString = '';
    $strigValue = json_encode($value);
    for ($i = 0; $i < strlen($strigValue); $i++) {
        if (
            $strigValue[$i] != "'" && $strigValue[$i] != "\""
            && $strigValue[$i] != "{" && $strigValue[$i] != "}"
        ) {
            if ($strigValue[$i] == ":") {
                $resultString .= ": ";
                continue;
            }
            $resultString .= $strigValue[$i];
        }
    }
    return "{\n{$indentetion}{$indentetion}      $resultString\n{$indentetion}{$indentetion}  }";
}
