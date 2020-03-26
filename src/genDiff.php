<?php

namespace Differ\GenDiff;

function genDiff($paths)
{
    $secondFile = json_decode(file_get_contents($paths[0]), true);
    $firstFile = json_decode(file_get_contents($paths[1]), true);
    $difrents = [];
    foreach ($firstFile as $key => $value) {
        if (array_key_exists($key, $secondFile)) {
            if ($firstFile[$key] == $secondFile[$key]) {
                $difrents[$key] = "    $key: $value";
            } else {
                $difrents["-$key"] = "    -$key: $value";
                $difrents[$key] = "    +$key: $secondFile[$key]";
            }
        } else {
            $difrents[] = "    -$key: $value";
        }
    }
    foreach ($secondFile as $key => $value) {
        if (!array_key_exists($key, $difrents)) {
            $difrents[] = "    +$key: $value";
        } 
    }
    $stringDiffrents = implode(",\n", $difrents);
    return "{\n$stringDiffrents\n}" . PHP_EOL;
}

/*$a = genDiff(["src/after.json", "src/before.json"]);
print_r($a);*/