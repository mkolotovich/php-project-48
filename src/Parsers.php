<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): mixed
{
    switch ($format) {
        case 'json':
            return json_decode($data);
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Incorrect input data! Needed json or yaml data.");
    }
}
