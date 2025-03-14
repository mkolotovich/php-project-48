<?php

namespace Gendiff\Formatters\Index;

use function Gendiff\Formatters\Stylish\stylish;
use function Gendiff\Formatters\Plain\plain;
use function Gendiff\Formatters\Json\jsonFormatter;

/**
 * @param array<mixed> $structure
 */

function formatData(string $formatName, array $structure): mixed
{
    return match ($formatName) {
        'plain' => plain($structure),
        'json' => jsonFormatter($structure),
        'stylish' => stylish($structure),
        default => throw new \Exception("incorrect format: '{$formatName}!"),
    };
}
