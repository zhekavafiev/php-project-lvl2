<?php

namespace Differ\ProcessingTree;

require_once __DIR__ . '/../src/autoload.php';

use function \Funct\Collection\flatten;


$paths = [
    'pathsbefore' => "tests/fixtures/afternested.json",
    'pathsafter' => "tests/fixtures/beforenested.json"
];

$pathToBefore = json_decode(file_get_contents($paths['pathsbefore']), true);
$pathToAfter = json_decode(file_get_contents($paths['pathsafter']), true);
/*
function findChangedElements(&$before, $after)
{
    if (is_array($after)) {
        foreach ($after as $key => $value) {
            if (array_key_exists($key, $before) && is_array($value)) {
                findChangedElements($before[$key], $after[$key]);
            } elseif (array_key_exists($key, $before) && !is_array($value)) {
                if ($before[$key] != $value) {
                    $before["-$key"] = $before[$key];
                    unset($before[$key]);
                    $before["+$key"] = $value;
                }
            }
        }
    }
    return $before;
}

function findAddElements(&$data1, $data2)
{
    if (is_array($data2)) {
        foreach ($data2 as $key => $value) {
            if (!array_key_exists($key, $data1)) {
                $newKeys["+$key"] = $value;
                $data1 = array_merge($data1, $newKeys);
            } elseif (array_key_exists($key, $data1) && is_array($value)) {
                findAddElements($data1[$key], $data2[$key]);
            } elseif (array_key_exists($key, $data1) && !is_array($value)) {
                if ($data1[$key] != $value) {
                    $data1["-$key"] = $data1[$key];
                    unset($data1[$key]);
                    $data1["+$key"] = $value;
                }
            }
        }
    }
    return $data1;
}

function findDeleteElements(&$before, $after)
{
    if (is_array($before)) {
        foreach ($before as $key => $value) {
            if (!array_key_exists($key, $after)) {
                $before["-$key"] = $before[$key];
                unset($before[$key]);
            } elseif (array_key_exists($key, $after) && is_array($value)) {
                findDeleteElements($before[$key], $after[$key]);
            }
        }
    }
    return $before;
}
*/

function testWithReduce($before, $after)
{
    $asd = function ($node1, $node2, $acc) use (&$asd) {
        if (!is_array($node1) || !is_array($node2)) {
            return $acc;
        }

        $keysData1 = array_keys($node1);
        $keysData2 = array_keys($node2);
        $allKeys = array_unique(array_merge($keysData1, $keysData2));
        sort($allKeys);

        $addDescriptionNode = array_map(function ($key) use ($keysData2, $keysData1, $node1, $node2, $acc, &$asd) {
            
            if (!in_array($key, $keysData1) && in_array($key, $keysData2)) {
                $acc = [
                    'name' => $key,

                    'value' => $node2[$key],
                    'state' => "Remove"
                ];
                return $acc;
            }

            if (in_array($key, $keysData1) && !in_array($key, $keysData2)) {
                $acc = [
                    'name' => $key,

                    'value' => $node1[$key],
                    'state' => "Add"
                ];
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
                        'state' => 'changed'
                    ];
                } 
            }

            return $acc;
            },
            $allKeys
        );
        return $addDescriptionNode;
    };
    return $asd($before, $after, []);
}

$print = testWithReduce($pathToBefore, $pathToAfter);
// print_r($print);


function renderInJson($tree) 
{
    $acc = "{\n\t";
    $abc = function ($node, $acc) {
        $iter = array_map(function ($el) use (&$abc, &$acc) {
            if (array_key_exists('children', $el)) {
                $acc .= 'haha';
                // print_r($el['children']);
                return $abc($el['children'], $acc);
            }
        }, $node);
    };
    return abc($tree, $acc);
}
    // $reduce = function ($el, $acc) use (&$reduce) {
    //     if ($node['state'] = 'Add') {
    //         $string .= "+ {$node['name']}: {$node['value']}";
    //     }

    //     if ($node['state'] = 'Remove') {
    //         $string .= "- {$node['name']}: {$node['value']}";
    //     }

    //     if ($node['state'] = 'Changed') {
    //         $string .= "- {$node['name']}: {$node['oldValue']}";
    //         $string .= "+ {$node['name']}: {$node['newValue']}";
    //     }

    //     if ($node['state'] = 'NotChanged' && array_key_exists('children', $node)) {
    //        return array_reduce(
    //            $node['children'],
    //            function ($newAcc, $newNode) use (&$reduce) {
    //                return $reduce($newNode, $newAcc);
    //             },
    //         $string);
    //     }

    //     return $string;
    // };    

print_r(renderInJson($print));