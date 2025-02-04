<?php

namespace Gendiff\Gendiff;

use function Gendiff\ReadFile\readFile;
use function Gendiff\Parser\parse;
use function Gendiff\MakeTree\buildTree;
use function Gendiff\Formatters\Index\formatData;

function getData($filePath)
{
    $data = readFile($filePath);
    return parse($data);
}
    
function generateDiff($filePath1, $filePath2, $formatName = 'stylish')
{
    $parsedData1 = getData($filePath1);
    $parsedData2 = getData($filePath2);
    $tree = buildTree($parsedData1, $parsedData2);
    return formatData($formatName, $tree);
}
