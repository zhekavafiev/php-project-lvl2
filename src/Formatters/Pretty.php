<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    // print_r($tree);
    $acc = "{\n";
    $getString = function ($node, &$acc, $indentetion = " ") use (&$getString) {
        $getLine = array_reduce($node, function ($acc, $el) use (&$getString, $indentetion) {
            // print_r($el);
            $acc .= $indentetion;

            if (array_key_exists('newValue', $el) && is_array($el['newValue'])) {
                $el['newValue'] = upgradeArrayValue($el['newValue'], $indentetion);
            }
            if (array_key_exists('oldValue', $el) && is_array($el['oldValue'])) {
                $el['oldValue'] = upgradeArrayValue($el['oldValue'], $indentetion);
            }


            if ($el['type'] == 'Added') {
                if ($el['oldValue'] === true) {
                    $acc .= "$indentetion+ {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion+ {$el['name']}: {$el['oldValue']}\n";
                }
            }

            if ($el['type'] == 'Removed') {
                if ($el['newValue'] === true) {
                    $acc .= "$indentetion- {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion- {$el['name']}: {$el['newValue']}\n";
                }
            }

            if ($el['type'] == 'Changed') {
                $acc .= "$indentetion+ {$el['name']}: {$el['newValue']}\n";
                $acc .= "$indentetion$indentetion- {$el['name']}: {$el['oldValue']}\n";
            }

            if ($el['type'] == 'Unchanged' && empty($el['children'])) {
                if ($el['newValue'] === true) {
                    $acc .= "  $indentetion{$el['name']}: true\n";
                } else {
                    $acc .= "  $indentetion{$el['name']}: {$el['newValue']}\n";
                }
            }

            if ($el['type'] == 'Nested') {
                $acc .= "  $indentetion{$el['name']}: {\n";
                $newIndentetion = $indentetion . "  ";
                return "{$getString($el['children'], $acc, $newIndentetion)}{$indentetion}   }\n";
            }
            return $acc;
        }, $acc);
        return $getLine;
    };

    return $getString($tree, $acc) . "}\n";
}

function upgradeArrayValue($value, $otstup = '  ')
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
    return "{\n{$otstup}{$otstup}      $resultString\n{$otstup}{$otstup}  }";
}
