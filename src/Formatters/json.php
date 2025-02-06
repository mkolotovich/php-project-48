<?php

namespace Gendiff\Formatters\Json;

function jsonFormatter($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}
