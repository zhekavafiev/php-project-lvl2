<?php

namespace Differ\Formatters\Plain;

function render($tree)
{
    return implode('', iter($tree));
}

function iter($tree, $parent = '')
{
    $mapping = array_map(function ($node) use ($parent) {
        $parent .= $node['name'];
        $type = $node['type'];

        switch ($type) {
            case 'Added':
                $value = stringify($node['newValue']);
                return "Property '{$parent}' was added with value: '{$value}'\n";
            
            case 'Unchanged':
                break;
            
            case 'Removed':
                return "Property '{$parent}' was removed\n";
            
            case 'Changed':
                $newVal = stringify($node['newValue']);
                $oldval = stringify($node['oldValue']);
                return
                "Property '{$parent}' was changed. From '{$oldval}' to '{$newVal}'\n";
                break;
            
            case 'Nested':
                $parent .= '.';
                $stepOnDepth = iter($node['children'], $parent);
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
