<?php

namespace Differ\Formatters\ToStringWithBrace;

function renderIToStringWithBrace($tree)
{
    $acc = "{";
    $string = function ($node, &$acc, $indentetion = "    ") use (&$string) {
        $getLine = array_reduce($node, function ($acc, $el) use (&$string, $indentetion) {
            $acc .= $indentetion;

            if (array_key_exists('value', $el) && is_array($el['value'])) {
                $el['value'] = upgradeArrayValue($el['value'], $indentetion);
            }

            if ($el['state'] == 'Add') {
                $acc .= "\n$indentetion+ {$el['name']}: {$el['value']}";
            }

            if ($el['state'] == 'Remove') {
                $acc .= "\n$indentetion- {$el['name']}: {$el['value']}";
            }

            if ($el['state'] == 'NotChanged' && !array_key_exists('children', $el)) {
                $acc .= "\n$indentetion  {$el['name']}: {$el['value']}";
            }

            if ($el['state'] == 'Changed') {
                $acc .= "\n$indentetion- {$el['name']}: {$el['oldValue']}";
                $acc .= "\n$indentetion+ {$el['name']}: {$el['newValue']}";
            }

            if ($el['state'] == 'NotChanged' && array_key_exists('children', $el)) {
                $acc .= "\n  $indentetion{$el['name']}: {";
                $newIndentetion = $indentetion . "    ";
                return "{$string($el['children'], $acc, $newIndentetion)}\n$indentetion  }";
            }
            return $acc;
        }, $acc);
        return $getLine;
    };
    // $resultString = substr($string($tree, $acc));
    return $string($tree, $acc) . "\n}\n";
}

function upgradeArrayValue($value, $otstup = '')
{
    $resultString = '';
    $strigValue = json_encode($value);
    for ($i = 0; $i < strlen($strigValue); $i++) {
        if (
            $strigValue[$i] != "'" && $strigValue[$i] != "\"" && $strigValue[$i] != " "
            && $strigValue[$i] != "{" && $strigValue[$i] != "}"
        ) {
            $resultString .= $strigValue[$i];
        }
    }
    $result = implode("\n$otstup    ", explode(',', $resultString));
    return "{\n{$otstup}    $result\n{$otstup}  }";
}
