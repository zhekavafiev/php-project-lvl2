<?php

require_once __DIR__ . '/../src/autoload.php';

use function Differ\Parse\parse;
use function Differ\Differ\processingDataFromFiles;

$paths = [
    'pathsbefore' => "tests/fixtures/afternested.json",
    'pathsafter' => "tests/fixtures/beforenested.json"
];
$result = parse($paths);
//print_r($result);
$otvet = processingDataFromFiles($result);
print_r($otvet);