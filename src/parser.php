<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $format)
{
    switch ($format) {
        case 'json':
            return(json_decode($data, true));
        case 'yaml':
            return(Yaml::parse($data));
        case 'yml':
            return(Yaml::parse($data));
    }
}
