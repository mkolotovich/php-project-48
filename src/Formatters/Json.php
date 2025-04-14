<?php

namespace Gendiff\Formatters\Json;

/**
 * @param array<mixed> $data
 */

function jsonFormatter(array $data): string
{
    return json_encode($data, JSON_PRETTY_PRINT) ? json_encode($data, JSON_PRETTY_PRINT) : "";
}
