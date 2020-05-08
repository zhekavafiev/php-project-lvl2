<?php

namespace Differ\Formatters\Pretty;

function render($tree, $depth = 1)
{
    $firstdepth = iter($tree);
    $implode = implode("\n", $firstdepth);
    return "{\n$implode\n}\n";
}

function iter($tree, $depth = 1)
{
    $asd = array_map(function ($el) use ($depth) {
        $mul = $depth * 2;
        $otst = str_repeat(' ', $mul);
        $name = $el['name'];

        $children = $el['children'] ?? null;
        if ($children) {
            $implode = implode("\n", iter($children, $depth + 2));
            return "  $otst$name " . "{\n$implode\n$otst  }";
        }
        
        $newValue = $el['newValue'] ?? null;
        $oldValue = $el['oldValue'] ?? null;

        if (!$children) {
            $type = $el['type'];
            switch ($type) {
                case 'Added':
                    $value = stringify($newValue, $depth);
                    return "$otst+ {$el['name']}: {$value}";
                case 'Removed':
                    $value = stringify($oldValue, $depth);
                    return "$otst- {$el['name']}: {$value}";
                case 'Unchanged':
                    $value = stringify($oldValue, $depth);
                    return "$otst  {$el['name']}: {$value}";
                case 'Changed':
                    $old = stringify($oldValue, $depth);
                    $new = stringify($newValue, $depth);
                    return "$otst- {$el['name']}: {$old}\n$otst+ {$el['name']}: {$new}";
            }
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
function stringify($value, $depth)
{
    $mul = $depth * 2;
    $standart = str_repeat(' ', $mul);
    $big = str_repeat(' ', (($depth + 2) * 2));
    $type = gettype($value);
    
    switch ($type) {
        case 'string':
            return  $value;
        case 'array':
            $iter = implode(", ", $value);
            return "[$iter]";
        case 'boolean':
            return $type;
        case 'object':
            $depth += 2;
            $newMul = $depth * 2;
            $newStandart = str_repeat(' ', $newMul);
            $newBig = str_repeat(' ', (($depth + 2) * 2));
            $vars = get_object_vars($value);
            foreach ($vars as $key => $var) {
                $iter = stringify($var, $depth + 2);
                if (is_object($var)) {
                    $result[] = "{\n{$newStandart}  {$key}: {\n$newBig  {$iter}\n$newStandart  }";
                } elseif (is_array($var)) {
                    $result[] = "{\n{$newStandart}  {$key}: {$iter}\n$standart  }";
                } else {
                    $result[] = "$key: $iter";
                }
            }
            return implode("\n$newStandart  ", $result);
        default:
            return gettype($value);
    }
}
