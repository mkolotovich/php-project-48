<?php

namespace Gendiff\Formatters\Index;

use function Gendiff\Formatters\Stylish\stylish;
use function Gendiff\Formatters\Plain\plain;
use function Gendiff\Formatters\Json\jsonFormatter;

/**
 * @param array<mixed> $structure
 */

function formatData(string $formatName, array $structure)
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
