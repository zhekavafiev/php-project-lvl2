<?php

namespace Differ\Hundler;

use function Funct\Collection\union;

function buildAst($data1, $data2)
{
    $keys1 = array_keys(get_object_vars($data1));
    $keys2 = array_keys(get_object_vars($data2));
    $allKeys = union($keys1, $keys2);

    $addDescriptionNode = array_map(function ($key) use ($data1, $data2) {
        if (!property_exists($data1, $key)) {
            $type = 'Added';
            if (!is_object($data2->$key)) {
                $oldValue = $data2->$key;
                return createDescription(null, $oldValue, $key, $type);
            } elseif (is_object($data2->$key)) {
                $oldValue = (array) $data2->$key;
                return createDescription(null, $oldValue, $key, $type);
            }
        }

        if (!property_exists($data2, $key)) {
            $type = 'Removed';
            if (!is_object($data1->$key)) {
                $newValue = $data1->$key;
                return createDescription($newValue, null, $key, $type);
            } elseif (is_object($data1->$key)) {
                $newValue = (array) $data1->$key;
                return createDescription($newValue, null, $key, $type);
            }
        }

        if (is_object($data1->$key) && is_object($data2->$key)) {
            $type = 'Nested';
            $children = buildAst($data1->$key, $data2->$key);
            return createDescription(null, null, $key, $type, $children);
        }

        if (!is_object($data1->$key) && !is_object($data2->$key)) {
            if ($data1->$key == $data2->$key) {
                $type = 'Unchanged';
                $newValue = $data1->$key;
                return createDescription($newValue, null, $key, $type);
            } else {
                $type = 'Changed';
                $newValue = $data2->$key;
                $oldValue = $data1->$key;
                return createDescription($newValue, $oldValue, $key, $type);
            }
        }
    }, $allKeys);
    return $addDescriptionNode;
}

function createDescription($newValue, $oldValue, $key, $type, $children = null)
{
    return [
        'name' => $key,
        'type' => $type,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'children' => $children
    ];
}
