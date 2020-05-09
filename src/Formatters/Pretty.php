<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    $firstdepth = iter($tree);
    $implode = implode("\n", $firstdepth);
    return "{\n$implode\n}\n";
}

function iter($tree, $depth = 1)
{
    $asd = array_map(function ($el) use ($depth) {
        $mul = $depth * 2;
        $identation = str_repeat(' ', $mul);
        $name = $el['name'];

        $children = $el['children'] ?? null;
        
        if ($el['type'] == 'Nested') {
            $implode = implode("\n", iter($children, $depth + 2));
            return "  $identation$name: " . "{\n$implode\n$identation  }";
        }
        
        $newValue = $el['newValue'] ?? null;
        $oldValue = $el['oldValue'] ?? null;

        if (!$children) {
            $type = $el['type'];
            switch ($type) {
                case 'Added':
                    $value = stringify($newValue, $depth);
                    return "$identation+ {$el['name']}: " . $value;
                case 'Removed':
                    $value = stringify($oldValue, $depth);
                    return "$identation- {$el['name']}: " . $value;
                case 'Unchanged':
                    $value = stringify($oldValue, $depth);
                    return "$identation  {$el['name']}: " . $value;
                case 'Changed':
                    $old = stringify($oldValue, $depth);
                    $new = stringify($newValue, $depth);
                    return "$identation+ {$el['name']}: {$new}\n$identation- {$el['name']}: {$old}";
            }
        }
    }, $tree);
    return $asd;
}

function stringify($value, $depth)
{
    $multiplier = $depth * 2;
    $identation = str_repeat(' ', $multiplier);
    $type = gettype($value);
        
    switch ($type) {
        case 'array':
            $iter = implode(", ", $value);
            return "[$iter]";
        case 'boolean':
            if ($value == 1) {
                return "true";
            } else {
                return 'false';
            }
        case 'object':
            $depth += 2;
            $properties = array_keys(get_object_vars($value));
            $getValue = array_map(function ($key) use ($depth, $value) {
                $newMultiplier = $depth * 2;
                $newIdentation = str_repeat(' ', $newMultiplier);
                $iter = stringify($value->$key, $depth + 1);
                return "{$newIdentation}  {$key}: {$iter}";
            }, $properties);
            $newMultiplier = $depth * 2;
            $newIdentation = str_repeat(' ', $newMultiplier);
            $implode = implode("\n", $getValue);
            return "{\n$implode\n$identation  }";
        default:
            return $value;
    }
}
