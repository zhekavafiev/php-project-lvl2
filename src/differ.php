<?php

namespace Differ\Differ;

function processingDataFromFiles($data)
{
    ['type' => $type, 'pathsbefore' => $filesDataBefore, 'pathsafter' => $filesDataAfter] = $data;
    foreach ($filesDataBefore as $key => $value) {
        if (array_key_exists($key, $filesDataAfter)) {
            if ($filesDataBefore[$key] == $filesDataAfter[$key]) {
                $difrents[$key] = $value;
            } else {
                $difrents["-$key"] = $value;
                $difrents["+$key"] = $filesDataAfter[$key];
            }
        } else {
            $difrents["-$key"] = $value;
        }
    }
    foreach ($filesDataAfter as $key => $value) {
        if (!array_key_exists($key, $filesDataBefore)) {
            $difrents["+$key"] = $value;
        }
    }
    return [
        'type' => $type,
        'diffrents' => $difrents
    ];
}
