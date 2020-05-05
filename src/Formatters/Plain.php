<?php

namespace Differ\Formatters\Plain;

function render($tree)
{
    $acc = "";
    $res = function ($node, &$acc, $road = "") use (&$res) {
        $abc = array_reduce($node, function ($acc, $el) use (&$res, $road) {
            if ($el['state'] == 'Added') {
                $road .= "{$el['name']}";
                if (!is_array($el['value'])) {
                    $acc .=
                    "Propperty '{$road}' was added with value: '{$el['value']}'\n";
                } else {
                    $acc .=
                    "Propperty '{$road}' was added with value: 'complex value'\n";
                }
            }

            if ($el['state'] == 'Removed') {
                $road .= "{$el['name']}";
                $acc .= "Propperty '{$road}' was remove\n";
            }

            if ($el['state'] == 'Changed') {
                $road .= "{$el['name']}";
                $acc .=
                "Propperty '{$road}' was changed from '{$el['oldValue']}' to '{$el['newValue']}'\n";
            }

            if ($el['state'] == 'Unchanged' && array_key_exists('children', $el)) {
                $road .= "{$el['name']}.";
                return $res($el['children'], $acc, $road);
            }
            return $acc;
        }, $acc);
        return $abc;
    };
    return $res($tree, $acc);
}
