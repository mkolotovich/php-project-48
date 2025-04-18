<?php

namespace Gendiff\Formatters\Json;

/**
 * @param array<mixed> $data
 */

function formatToJson(array $data): mixed
{
    return json_encode($data, JSON_PRETTY_PRINT);
}
