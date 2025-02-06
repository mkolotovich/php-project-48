<?php

namespace Differ\Differ;

use function Gendiff\ReadFile\readFile;
use function Gendiff\Parsers\parse;
use function Gendiff\MakeTree\buildTree;
use function Gendiff\Formatters\Index\formatData;

function getData($filePath)
{
    $data = readFile($filePath);
    $format = new \SplFileInfo($filePath);
    return parse($data, $format->getExtension());
}

function genDiff($filePath1, $filePath2, $formatName = 'stylish')
{
    $parsedData1 = getData($filePath1);
    $parsedData2 = getData($filePath2);
    $tree = buildTree($parsedData1, $parsedData2);
    return formatData($formatName, $tree);
}
