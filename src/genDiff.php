<?php

namespace Differ\GenDiff;

function genDiff($paths)
{
    $secondFile = json_decode(file_get_contents($paths[0]), true);
    $firstFile = json_decode(file_get_contents($paths[1]), true);
    print_r($secondFile);
    $difrents = [];
    foreach ($firstFile as $key => $value) {
        if (array_key_exists($key, $secondFile)) {
            if ($firstFile[$key] == $secondFile[$key]) {
                $difrents[$key] = "\t$key: $value";
            } else {
                $difrents["-$key"] = "\t-$key: $value";
                $difrents[$key] = "\t+$key: $secondFile[$key]";
            }
        } else {
            $difrents[] = "\t-$key: $value";
        }
    }
    foreach ($secondFile as $key => $value) {
        if (!array_key_exists($key, $difrents)) {
            $difrents[] = "\t+$key: $value";
        }
    }
    $stringDiffrents = implode(",\n", $difrents);
    return "{\n$stringDiffrents\n}" . PHP_EOL;
}

/*$a = genDiff(["tests/fixtures/before.json", "tests/fixtures/after.json"]);
print_r($a);*/
