<?php

namespace Gendiff\Formatters\Index;

use function Gendiff\Formatters\Stylish\stylish;
use function Gendiff\Formatters\Plain\plain;

function formatData($formatName, $structure)
{
    if ($formatName === 'plain') {
        return plain($structure);
    }
    if ($formatName === 'json') {
        return json_formatter($structure);
    }
    if ($formatName == 'stylish') {
        return stylish($structure);
    }
    throw new \Exception("incorrect format: '{$formatName}!");
}
