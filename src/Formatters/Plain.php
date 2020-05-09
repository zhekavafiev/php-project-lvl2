<?php

namespace Differ\Formatters\Plain;

function render($tree)
{
    return iter($tree);
}

function iter($tree, $acc = '', $roadToProperties = '')
{
    $putDescription = array_reduce($tree, function ($acc, $node) use ($roadToProperties) {
        $type = $node['type'];
        $name = $node['name'];
        
        switch ($type) {
            case 'Added':
                $roadToProperties .= "{$name}";
                if (!is_object($node['newValue'])) {
                    $acc .=
                    "Property '{$roadToProperties}' was added with value: '{$node['newValue']}'\n";
                } else {
                    $acc .=
                    "Property '{$roadToProperties}' was added with value: 'complex value'\n";
                }
                break;
            
            case 'Unchanged':
                break;
            
            case 'Removed':
                $roadToProperties .= "{$name}";
                $acc .= "Property '{$roadToProperties}' was removed\n";
                break;
            
            case 'Changed':
                $roadToProperties .= "{$name}";
                $acc .=
                "Property '{$roadToProperties}' was changed. From '{$node['oldValue']}' to '{$node['newValue']}'\n";
                break;
            
            case 'Nested':
                $roadToProperties .= "{$name}.";
                return iter($node['children'], $acc, $roadToProperties);
            default:
                break;
        }
        return $acc;
    }, $acc);
    
    return $putDescription;
}
