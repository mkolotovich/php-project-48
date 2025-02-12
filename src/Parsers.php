<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): mixed
{
    switch ($format) {
        case 'json':
            return(json_decode($data, true));
        case 'yaml':
            return(Yaml::parse($data));
        case 'yml':
            return(Yaml::parse($data));
        default:
            throw new \Exception("incorrect file extension: '{$format}!");
    }
}
