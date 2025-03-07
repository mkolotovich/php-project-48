<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function normalizeValue(mixed $value): mixed
{
    $normalizedValue = array_map(function ($el) {
        if (gettype($el) === 'array') {
            return normalizeValue($el);
        }
        if ($el === false) {
            return 'false';
        } elseif ($el === true) {
            return 'true';
        } elseif ($el === null) {
            return 'null';
        } else {
            return $el;
        }
    }, $value);
   return $normalizedValue;
}

function parse(string $data, string $format): mixed
{
    switch ($format) {
        case 'json':
            $parcedData = json_decode($data, true);
            return normalizeValue($parcedData);
        case 'yaml':
            $parcedData = Yaml::parse($data);
            return normalizeValue($parcedData);
        case 'yml':
            $parcedData = Yaml::parse($data);
            return normalizeValue($parcedData);
        default:
            throw new \Exception("Incorrect input data! Needed json or yaml data.");
    }
}
