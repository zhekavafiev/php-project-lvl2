<?php

namespace Differ\Formatters\Plain;

function render($tree)
{
    return implode('', iter($tree));
}

function iter($tree, $ancestry = '')
{
    $mapping = array_map(function ($node) use ($ancestry) {
        $newAncestry = $ancestry . $node['name'];
        $type = $node['type'];

        switch ($type) {
            case 'Added':
                $value = stringify($node['newValue']);
                return "Property '{$newAncestry}' was added with value: '{$value}'\n";
            
            case 'Unchanged':
                break;
            
            case 'Removed':
                return "Property '{$newAncestry}' was removed\n";
            
            case 'Changed':
                $newVal = stringify($node['newValue']);
                $oldval = stringify($node['oldValue']);
                return
                "Property '{$newAncestry}' was changed. From '{$oldval}' to '{$newVal}'\n";
                break;
            
            case 'Nested':
                $newAncestry .= '.';
                $stepOnDepth = iter($node['children'], $newAncestry);
                return implode('', $stepOnDepth);
            default:
                break;
        }
    }, $tree);
    
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
