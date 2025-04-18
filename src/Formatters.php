<?php

namespace Gendiff\Formatters\Index;

use function Gendiff\Formatters\Stylish\formatToStylish;
use function Gendiff\Formatters\Plain\formatToPlain;
use function Gendiff\Formatters\Json\formatToJson;

/**
 * @param array<mixed> $structure
 */

function formatData(string $formatName, array $structure): mixed
{
    return match ($formatName) {
        'plain' => formatToPlain($structure),
        'json' => formatToJson($structure),
        'stylish' => formatToStylish($structure),
        default => throw new \Exception("incorrect format: '{$formatName}!"),
    };
}
