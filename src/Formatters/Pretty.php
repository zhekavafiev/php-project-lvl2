<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    $acc = "{\n";
    $getString = function ($node, &$acc, $indentetion = " ") use (&$getString) {
        $getLine = array_reduce($node, function ($acc, $el) use (&$getString, $indentetion) {
            $acc .= $indentetion;

            if (array_key_exists('value', $el) && is_array($el['value'])) {
                $el['value'] = upgradeArrayValue($el['value'], $indentetion);
            }

            if ($el['state'] == 'Added') {
                if ($el['value'] === true) {
                    $acc .= "$indentetion+ {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion+ {$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'Removed') {
                if ($el['value'] === true) {
                    $acc .= "$indentetion- {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion- {$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'Changed') {
                $acc .= "$indentetion+ {$el['name']}: {$el['newValue']}\n";
                $acc .= "$indentetion$indentetion- {$el['name']}: {$el['oldValue']}\n";
            }

            if ($el['state'] == 'Unchanged' && !array_key_exists('children', $el)) {
                if ($el['value'] === true) {
                    $acc .= "  $indentetion{$el['name']}: true\n";
                } else {
                    $acc .= "  $indentetion{$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'Unchanged' && array_key_exists('children', $el)) {
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
