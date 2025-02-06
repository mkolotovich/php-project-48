<?php

namespace Gendiff\Formatters\Index;

use function Gendiff\Formatters\Stylish\stylish;
use function Gendiff\Formatters\Plain\plain;
use function Gendiff\Formatters\Json\jsonFormatter;

function formatData($formatName, $structure)
{
    if ($formatName === 'plain') {
        return plain($structure);
    }
    if ($formatName === 'json') {
        return jsonFormatter($structure);
    }
    if ($formatName == 'stylish') {
        return stylish($structure);
    }
    throw new \Exception("incorrect format: '{$formatName}!");
}
