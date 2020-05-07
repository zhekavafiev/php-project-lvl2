<?php

namespace Differ\Hundler;

use function Funct\Collection\union;

function buildAst($data1, $data2)
{
    $keys1 = array_keys(get_object_vars($data1));
    $keys2 = array_keys(get_object_vars($data2));
    $allKeys = union($keys1, $keys2);

    $addDescriptionTree = array_map(function ($key) use ($data1, $data2) {
        $newValue = $data2->$key ?? null;
        $oldValue = $data1->$key ?? null;
        if (!$oldValue) {
            $type = 'Added';
            return createNode($key, $type, $newValue);
        }

        if (!$newValue) {
            $type = 'Removed';
            return createNode($key, $type, null, $oldValue);
        }

        if (is_object($oldValue) && is_object($newValue)) {
            $type = 'Nested';
            $children = buildAst($oldValue, $newValue);
            return createNode($key, $type, null, null, $children);
        }

        if ($oldValue == $newValue) {
            $type = 'Unchanged';
            return createNode($key, $type, null, $oldValue);
        } else {
            $type = 'Changed';
            return createNode($key, $type, $newValue, $oldValue);
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
