<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\compact;

function render($tree)
{
    return implode("\n", (iter($tree)));
}

function iter($tree, $ancestry = '')
{
    $filtered = array_filter($tree, function ($node) {
        return $node['type'] != 'Unchanged';
    });

    $mapping = array_map(function ($node) use ($ancestry) {
        $newAncestry = $ancestry . $node['name'];
        $type = $node['type'];

        switch ($type) {
            case 'Added':
                $value = stringify($node['newValue']);
                $formatView = "Property '%s' was added with value: '%s'";
                return sprintf($formatView, $newAncestry, $value);
            
            case 'Removed':
                return "Property '{$newAncestry}' was removed";
            
            case 'Changed':
                $newVal = stringify($node['newValue']);
                $oldval = stringify($node['oldValue']);
                $formatView = "Property '%s' was changed. From '%s' to '%s'";
                return sprintf($formatView, $newAncestry, $oldval, $newVal);
                
            case 'Nested':
                $newAncestry .= '.';
                $result = (iter($node['children'], $newAncestry));
                return implode("\n", $result);
            default:
                break;
        }
    }, $filtered);
    
    return $mapping;
}

function stringify($value)
{
    $type = gettype($value);
        
    switch ($type) {
        case 'array':
        case 'object':
            return "complex value";
        case 'boolean':
            return $value ? 'true' : 'false';
        default:
            return $value;
    }
}
