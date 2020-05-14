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
        $identationMultiplier = $depth * 4;
        $smallIdententionMultiplier = $identationMultiplier - 2;
        $identation = str_repeat(' ', $identationMultiplier);
        $smallIdentation = str_repeat(' ', $smallIdententionMultiplier);
        
        $name = $node['name'];
        $type = $node['type'];
        
        if ($type == 'Nested') {
            $stepOnDepth = implode("\n", iter($node['children'], $depth + 1));
            return "{$identation}{$name}: " . "{\n{$stepOnDepth}\n{$identation}}";
        }
        
        $newValue = $node['newValue'] ?? null;
        $oldValue = $node['oldValue'] ?? null;

        switch ($type) {
            case 'Added':
                $value = stringify($newValue, $depth);
                $format = "%s+ %s: %s";
                return sprintf($format, $smallIdentation, $name, $value);
            case 'Removed':
                $value = stringify($oldValue, $depth);
                $format = "%s- %s: %s";
                return sprintf($format, $smallIdentation, $name, $value);
            case 'Unchanged':
                $value = stringify($oldValue, $depth);
                $format = "%s%s: %s";
                return sprintf($format, $identation, $name, $value);
            case 'Changed':
                $old = stringify($oldValue, $depth);
                $new = stringify($newValue, $depth);
                $forrmatNew = "%s+ %s: %s";
                $formatOld = "%s- %s: %s";
                
                $value = [
                    sprintf($forrmatNew, $smallIdentation, $name, $new),
                    sprintf($formatOld, $smallIdentation, $name, $old)
                ];
                return implode("\n", $value);
        }
    }, $tree);
    return $mapped;
}

function stringify($value, $depth)
{
    $identationMultiplier = $depth * 4;
    $identation = str_repeat(' ', $identationMultiplier);
    $type = gettype($value);
        
    switch ($type) {
        case 'array':
            $formattedValue = implode(", ", $value);
            return "[$formattedValue]";
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'object':
            $newDepth = $depth + 1;
            $properties = array_keys(get_object_vars($value));

            $data = array_map(function ($key) use ($newDepth, $value) {
                $newIdentationMultiplier = $newDepth * 4;
                $newIdentation = str_repeat(' ', $newIdentationMultiplier);
                $formattedValue = stringify($value->$key, $newDepth + 1);
                return "{$newIdentation}{$key}: {$formattedValue}";
            }, $properties);
            
            $result = implode("\n", $data);
            return "{\n$result\n$identation}";
        default:
            return $value;
    }
}
