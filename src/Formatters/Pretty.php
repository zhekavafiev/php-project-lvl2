<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    $result = implode("\n", iter($tree));
    return "{\n$result\n}\n";
}

function iter($tree, $depth = 1)
{
    $mapped = array_map(function ($node) use ($depth) {
        $identationMultiplier = $depth * 2;
        $identation = str_repeat(' ', $identationMultiplier);
        $name = $node['name'];
        $type = $node['type'];
        
        if ($type == 'Nested') {
            $stepOnDepth = implode("\n", iter($node['children'], $depth + 2));
            return "  $identation$name: " . "{\n$stepOnDepth\n$identation  }";
        }
        
        $newValue = $node['newValue'] ?? null;
        $oldValue = $node['oldValue'] ?? null;

        switch ($type) {
            case 'Added':
                $value = stringify($newValue, $depth);
                return "$identation+ {$name}: " . $value;
            case 'Removed':
                $value = stringify($oldValue, $depth);
                return "$identation- {$name}: " . $value;
            case 'Unchanged':
                $value = stringify($oldValue, $depth);
                return "$identation  {$name}: " . $value;
            case 'Changed':
                $old = stringify($oldValue, $depth);
                $new = stringify($newValue, $depth);
                $value = [
                    "$identation+ {$name}: {$new}",
                    "$identation- {$name}: {$old}"
                ];
                return implode("\n", $value);
        }
    }, $tree);
    return $mapped;
}

function stringify($value, $depth)
{
    $identationMultiplier = $depth * 2;
    $identation = str_repeat(' ', $identationMultiplier);
    $type = gettype($value);
        
    switch ($type) {
        case 'array':
            $iter = implode(", ", $value);
            return "[$iter]";
        case 'boolean':
            return ($value === true) ? 'true' : 'false';
        case 'object':
            $depth += 2;
            $properties = array_keys(get_object_vars($value));
            $getValue = array_map(function ($key) use ($depth, $value) {
                $newIdentationMultiplier = $depth * 2;
                $newIdentation = str_repeat(' ', $newIdentationMultiplier);
                $iter = stringify($value->$key, $depth + 1);
                return "{$newIdentation}  {$key}: {$iter}";
            }, $properties);
            $implode = implode("\n", $getValue);
            return "{\n$implode\n$identation  }";
        default:
            return $value;
    }
}
