<?php

namespace Gendiff\Formatters\Json;

/**
 * @param array<mixed> $data
 */

function jsonFormatter(array $data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}
