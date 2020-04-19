<?php

namespace Differ\Formatters\ToStringWithBrace;

function renderIToStringWithBrace($tree)
{
    $acc = "{\n";
    $string = function ($node, &$acc, $indentetion = " ") use (&$string) {
        $getLine = array_reduce($node, function ($acc, $el) use (&$string, $indentetion) {
            $acc .= $indentetion;

            if (array_key_exists('value', $el) && is_array($el['value'])) {
                $el['value'] = upgradeArrayValue($el['value'], $indentetion);
            }

            if ($el['state'] == 'Add') {
                if ($el['value'] === true) {
                    $acc .= "$indentetion+ {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion+ {$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'Remove') {
                if ($el['value'] === true) {
                    $acc .= "$indentetion- {$el['name']}: true\n";
                } else {
                    $acc .= "$indentetion- {$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'Changed') {
                $acc .= "$indentetion- {$el['name']}: {$el['oldValue']}\n";
                $acc .= "$indentetion$indentetion+ {$el['name']}: {$el['newValue']}\n";
            }

            if ($el['state'] == 'NotChanged' && !array_key_exists('children', $el)) {
                if ($el['value'] === "true") {
                    $acc .= "  $indentetion{$el['name']}: true\n";
                } else {
                    $acc .= "  $indentetion{$el['name']}: {$el['value']}\n";
                }
            }

            if ($el['state'] == 'NotChanged' && array_key_exists('children', $el)) {
                $acc .= "  $indentetion{$el['name']}: {\n";
                $newIndentetion = $indentetion . "  ";
                return "{$string($el['children'], $acc, $newIndentetion)}{$indentetion}   }\n";
            }
            return $acc;
        }, $acc);
        return $getLine;
    };

    return $string($tree, $acc) . "}\n";
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
