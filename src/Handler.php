<?php

namespace Differ\Hundler;

function buildingAst($before, $after)
{
    $asd = function ($node1, $node2, $acc) use (&$asd) {
        if (!is_array($node1) || !is_array($node2)) {
            return $acc;
        }

        $keysData1 = array_keys($node1);
        // print_r($keysData1);

        $keysData2 = array_keys($node2);
        // print_r($keysData2);

        // $allKeys = collect($keysData1)->concat($keysData2)->all();
        $allKeys = array_unique(array_merge($keysData1, $keysData2));
        sort($allKeys);
        // print_r($allKeys);
        
        $addDescriptionNode = array_map(function ($key) use ($keysData2, $keysData1, $node1, $node2, $acc, &$asd) {
            if (!in_array($key, $keysData1) && in_array($key, $keysData2)) {
                if (is_array($node2[$key])) {
                    $acc = [
                        'name' => $key,
                        'value' => ($node2[$key]),
                        'state' => "Remove"
                    ];
                } else {
                    $acc = [
                        'name' => $key,
    
                        'value' => $node2[$key],
                        'state' => "Remove"
                    ];
                }
                return $acc;
            }

            if (in_array($key, $keysData1) && !in_array($key, $keysData2)) {
                if (is_array($node1[$key])) {
                    $acc = [
                        'name' => $key,
                        'value' => ($node1[$key]),
                        'state' => "Add"
                    ];
                } else {
                    $acc = [
                        'name' => $key,
                        'value' => $node1[$key],
                        'state' => "Add"
                    ];
                }
                return $acc;
            }



            if (is_array($node1[$key]) && is_array($node2[$key])) {
                $acc = [
                    'name' => $key,
                    'state' => "NotChanged",
                    'children' => $asd($node1[$key], $node2[$key], $acc)
                ];
            }


            if (!is_array($node1[$key]) && !is_array($node2[$key])) {
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
    return $asd($before, $after, []);
}
