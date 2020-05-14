<?php

namespace Differ\Formatters\Pretty;

function render($tree)
{
    $result = implode("\n", iter($tree));
    return "{\n$result\n}";
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
                $formatRezult = "%s+ %s: %s";
                return sprintf($formatRezult, $identation, $name, $value);
            case 'Removed':
                $value = stringify($oldValue, $depth);
                $formatRezult = "%s- %s: %s";
                return sprintf($formatRezult, $identation, $name, $value);
            case 'Unchanged':
                $value = stringify($oldValue, $depth);
                $formatRezult = "%s  %s: %s";
                return sprintf($formatRezult, $identation, $name, $value);
            case 'Changed':
                $old = stringify($oldValue, $depth);
                $new = stringify($newValue, $depth);
                $forrmatNew = "%s+ %s: %s";
                $formatOld = "%s- %s: %s";
                
                $value = [
                    sprintf($forrmatNew, $identation, $name, $new),
                    sprintf($formatOld, $identation, $name, $old)
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
            $formattedValue = implode(", ", $value);
            return "[$formattedValue]";
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'object':
            $depth += 2;
            $properties = array_keys(get_object_vars($value));
            $data = array_map(function ($key) use ($depth, $value) {
                $newIdentationMultiplier = $depth * 2;
                $newIdentation = str_repeat(' ', $newIdentationMultiplier);
                $formattedValue = stringify($value->$key, $depth + 1);
                return "{$newIdentation}  {$key}: {$formattedValue}";
            }, $properties);
            $result = implode("\n", $data);
            return "{\n$result\n$identation  }";
        default:
            return $value;
    }
}
