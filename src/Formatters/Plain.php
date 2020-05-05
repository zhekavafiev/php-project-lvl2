<?php

namespace Differ\Formatters\Plain;

function render($tree)
{
    $acc = "";
    $res = function ($node, &$acc, $road = "") use (&$res) {
        $abc = array_reduce($node, function ($acc, $el) use (&$res, $road) {
            if ($el['type'] == 'Added') {
                $road .= "{$el['name']}";
                if (!is_array($el['oldValue'])) {
                    $acc .=
                    "Property '{$road}' was added with value: '{$el['oldValue']}'\n";
                } else {
                    $acc .=
                    "Property '{$road}' was added with value: 'complex value'\n";
                }
            }

            if ($el['type'] == 'Removed') {
                $road .= "{$el['name']}";
                $acc .= "Property '{$road}' was removed\n";
            }

            if ($el['type'] == 'Changed') {
                $road .= "{$el['name']}";
                $acc .=
                "Property '{$road}' was changed. From '{$el['oldValue']}' to '{$el['newValue']}'\n";
            }

            if ($el['type'] == 'Nested') {
                $road .= "{$el['name']}.";
                return $res($el['children'], $acc, $road);
            }
            return $acc;
        }, $acc);
        return $abc;
    };
    return $res($tree, $acc);
}
