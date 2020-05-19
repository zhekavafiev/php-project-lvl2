<?php

namespace Differ\Hundler;

use function Funct\Collection\union;

function buildAst($data1, $data2)
{
    $keys1 = array_keys(get_object_vars($data1));
    $keys2 = array_keys(get_object_vars($data2));
    $allKeys = union($keys1, $keys2);

    $addDescriptionTree = array_map(function ($key) use ($data1, $data2) {
        if (!property_exists($data1, $key)) {
            return createNode($key, 'Added', $data2->$key);
        }

        if (!property_exists($data2, $key)) {
            return createNode($key, 'Removed', null, $data1->$key);
        }

        $newValue = $data2->$key;
        $oldValue = $data1->$key;

        if (is_object($oldValue) && is_object($newValue)) {
            $children = buildAst($oldValue, $newValue);
            return createNode($key, 'Nested', null, null, $children);
        }

        if ($oldValue == $newValue) {
            return createNode($key, 'Unchanged', null, $oldValue);
        } else {
            return createNode($key, 'Changed', $newValue, $oldValue);
        }
    }, $allKeys);
    return $addDescriptionTree;
}

function createNode($key, $type, $newValue, $oldValue = null, $children = null)
{
    return [
        'name' => $key,
        'type' => $type,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'children' => $children
    ];
}
