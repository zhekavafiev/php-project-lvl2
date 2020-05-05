<?php

namespace Differ\Hundler;

use function Funct\Collection\union;

function buildAst($node1, $node2)
{
    $allKeys = collect($node1)->union($node2)->keys()->all();
    $addDescriptionNode = array_map(function ($key) use ($node1, $node2) {
        if (!property_exists($node1, $key)) {
            if (!is_object($node2->$key)) {
                return [
                    'name' => $key,
                    'value' => $node2->$key,
                    'state' => "Added"
                ];
            } elseif (is_object($node2->$key)) {
                return [
                    'name' => $key,
                    'value' => (array) $node2->$key,
                    'state' => "Added"
                ];
            }
        }

        if (!property_exists($node2, $key)) {
            if (!is_object($node1->$key)) {
                return [
                    'name' => $key,
                    'value' => $node1->$key,
                    'state' => "Removed"
                ];
            } elseif (is_object($node1->$key)) {
                return [
                    'name' => $key,
                    'value' => (array) $node1->$key,
                    'state' => "Removed"
                ];
            }
        }

        if (is_object($node1->$key) && is_object($node2->$key)) {
            return [
                'name' => $key,
                'state' => "Unchanged",
                'children' => buildAst($node1->$key, $node2->$key)
            ];
        }

        if (!is_object($node1->$key) && !is_object($node2->$key)) {
            if ($node1->$key == $node2->$key) {
                return [
                    'name' => $key,
                    'value' => $node1->$key,
                    'state' => "Unchanged"
                ];
            } else {
                return [
                    'name' => $key,
                    'oldValue' => $node1->$key,
                    'newValue' => $node2->$key,
                    'state' => 'Changed'
                ];
            }
        }
    }, $allKeys);
    return $addDescriptionNode;
}
