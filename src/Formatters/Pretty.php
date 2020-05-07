<?php

namespace Differ\Formatters\Pretty;

function iter($tree, $depth = 1)
{
    $asd = array_map(function ($el) use ($depth) {
        if ($el['type'] == 'Nested') {
            $children = iter($el['children'], $depth + 1);
            $result = implode("", $children);
            return "{$depth} {$el['name']}: {\n{$result}{$depth}  }";
        }

        $newValue = json_encode($el['newValue']) ?? null;
        $oldValue = json_encode($el['oldValue']) ?? null;

        if ($el['type'] == 'Added') {
            $string = "{$depth}+ {$el['name']}:{$newValue}\n";
            return stringify($string, $depth);
        }

        if ($el['type'] == 'Removed') {
            $string = "{$depth}- {$el['name']}:{$oldValue}\n";
            return stringify($string, $depth);
        }

        if ($el['type'] == 'Unchanged') {
            $string = "{$depth}  {$el['name']}:{$oldValue}\n";
            return stringify($string, $depth);
        }

        if ($el['type'] == 'Changed') {
            $string = "{$depth}- {$el['name']}: {$oldValue}|+ {$el['name']}: {$newValue}\n";
            return stringify($string, $depth);
        }
    }, $tree);
    return $asd;
}

/*function render($tree)
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
*/
function stringify($string, $depth)
{
    $bracketDelete = str_replace("\"", '', $string);
    $updateDoubleDot = str_replace(":", ": ", $bracketDelete);
    $newDepth = $depth + 1;
    $updateOpenBrase = str_replace("{", "{\n{$newDepth}  ", $updateDoubleDot);
    $updateCloseBrase = str_replace("}", "\n{$depth}  }", $updateOpenBrase);
    $updateBlancLine = str_replace("|", "\n{$depth}  ", $updateCloseBrase);
    return $updateBlancLine;
}
