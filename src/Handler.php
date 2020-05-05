<?php

namespace Differ\Hundler;

use function Funct\Collection\union;

function buildAst($before, $after)
{
    $buildAst = function ($node1, $node2, $acc) use (&$buildAst) {
        if (!is_object($node1) || !is_object($node2)) {
            return $acc;
        }
        if (is_object($node1)) {
            $node1 = (array) $node1;
        }
        if (is_object($node2)) {
            $node2 = (array) $node2;
        }

        $keysData1 = array_keys($node1);
        $keysData2 = array_keys($node2);
        
        $allKeys = union($keysData1, $keysData2);
        sort($allKeys);
        
        $addDescriptionNode = array_map(function ($key) use ($keysData2, $keysData1, $node1, $node2, $acc, &$buildAst) {
            print_r($node1);
            print_r($node2);
            if (!in_array($key, $keysData1)) {
                if (!is_object($node2[$key])) {
                    $acc = [
                        'name' => $key,
                        'value' => $node2[$key],
                        'state' => "Remove"
                    ];
                } else {
                    $acc = [
                        'name' => $key,
                        'value' => (array) $node2[$key],
                        'state' => "Remove"
                    ];
                }
                return $acc;
            }

            if (!in_array($key, $keysData2)) {
                if (!is_object($node1[$key])) {
                    $acc = [
                        'name' => $key,
                        'value' => $node1[$key],
                        'state' => "Add"
                    ];
                } else {
                    $acc = [
                        'name' => $key,
                        'value' => (array) $node1[$key],
                        'state' => "Add"
                    ];
                }
                return $acc;
            }

            if (is_object($node1[$key]) && is_object($node2[$key])) {
                $acc = [
                    'name' => $key,
                    'state' => "NotChanged",
                    'children' => $buildAst($node1[$key], $node2[$key], $acc)
                ];
            }

            if (!is_object($node1[$key]) && !is_object($node2[$key])) {
                if ($node1[$key] == $node2[$key]) {
                    $acc = [
                        'name' => $key,
                        'value' => $node1[$key],
                        'state' => "NotChanged"
                    ];
                } else {
                    $acc = [
                        'name' => $key,
                        'oldValue' => $node1[$key],
                        'newValue' => $node2[$key],
                        'state' => 'Changed'
                    ];
                }
            }

            return $acc;
        }, $allKeys);
        return $addDescriptionNode;
    };
    return $buildAst($before, $after, []);
}
