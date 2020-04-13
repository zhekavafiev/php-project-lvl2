<?php

namespace Differ\Formatters\ToJson;

function renderToJson($tree)
{
    return json_encode($tree, JSON_PRETTY_PRINT);
}
