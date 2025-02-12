<?php

namespace Differ\Differ;

use function Gendiff\ReadFile\readFile;
use function Gendiff\Parsers\parse;
use function Gendiff\MakeTree\buildTree;
use function Gendiff\Formatters\Index\formatData;

/**
* @return array<mixed>
*/
function getData(string $filePath): array
{
    $data = readFile($filePath);
    $format = new \SplFileInfo($filePath);
    return [$data, $format->getExtension()];
}

function genDiff(string $filePath1, string $filePath2, string $formatName = 'stylish'): string
{
    [$file1, $ext1] = getData($filePath1);
    [$file2, $ext2] = getData($filePath2);
    $parsedData1 = parse($file1, $ext1);
    $parsedData2 = parse($file2, $ext2);
    $tree = buildTree($parsedData1, $parsedData2);
    return formatData($formatName, $tree);
}
