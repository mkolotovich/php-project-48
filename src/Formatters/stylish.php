<?php

namespace Gendiff\Formatters\Stylish;

const SPACE = 2;
const DEPTHSTPACE = 4;

/**
 * @param array<mixed> $currentValue
 */
function callBack(array $currentValue, string $replaceInner, int $depth)
{
    $entries = array_keys($currentValue);
    return array_reduce($entries, function ($acc, $key) use ($replaceInner, $depth, $currentValue) {
        $val = $currentValue[$key];
        if (gettype($val) !== 'array') {
            $newAcc = str_repeat($replaceInner, $depth) . "{$key}: {$val}\n";
        } else {
            $newAcc = str_repeat($replaceInner, $depth) . "{$key}: ";
            $newAcc .= callBack($val, $replaceInner, $depth + \Gendiff\Formatters\Stylish\DEPTHSTPACE);
            $newAcc .= str_repeat($replaceInner, $depth) . "}\n";
        }
        return $acc . $newAcc;
    }, "{\n");
}

function stringify(mixed $value, string $replacer = ' ', int $spaceCount = 1)
{
    if (gettype($value) !== 'array') {
        return "{$value}";
    }
    $res = callBack($value, $replacer, $spaceCount);
    $res .= str_repeat(' ', $spaceCount - \Gendiff\Formatters\Stylish\DEPTHSTPACE) . "}";
    return $res;
}
/**
 * @param array<mixed> $item
 */
function mkStr(array $item, int $depth)
{
    if ($item["type"] == 'nested') {
        $result = str_repeat(
            ' ',
            \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE
        );
        $result .= "  {$item["key"]}: {\n";
        return $result;
    }
    return '';
}
/**
 * @param array<mixed> $data
 */
function cb(array $data, string $result = '', int $depth = 0)
{
    $key = $data['key'];
    $type = $data['type'];
    $children = $data['children'];
    $printVal = array_key_exists("value", $data) ?
        stringify($data['value'], ' ', ($depth + 1) * \Gendiff\Formatters\Stylish\DEPTHSTPACE) : null;
    $printNewVal = array_key_exists("newValue", $data) ? stringify($data['newValue'], ' ', ($depth + 1) * \Gendiff\Formatters\Stylish\DEPTHSTPACE) : null;
    switch ($type) {
        case 'root':
            $child = array_map(fn($item) => cb($item, mkStr($item, $depth + 1), $depth + 1), $children);
            $res = "{\n{$result}" . implode("\n", $child) . "\n" ;
            $res .= str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\SPACE * $depth * \Gendiff\Formatters\Stylish\SPACE
            ) . "}";
            return $res;
        case 'nested':
            $child = array_map(fn($item) => cb($item, mkStr($item, $depth + 1), $depth + 1), $children);
            $res = "{$result}" . implode("\n", $child) . "\n";
            $res .= str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\SPACE * $depth * \Gendiff\Formatters\Stylish\SPACE
            ) . "}";
            return $res;
        case 'updated':
            $res = "{$result}";
            $res .= str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE
            ) . "- {$key}";
            $res .= ": {$printVal}\n";
            $res .= str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE
            ) . "+ {$key}";
            $res .= ": {$printNewVal}";
            return $res;
        case 'added':
            $res = "{$result}" . str_repeat(
                ' ',
                (\Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE)
            );
            $res .= "+ {$key}: {$printVal}";
            return $res;
        case 'removed':
            $res = "{$result}" . str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE
            );
            $res .= "- {$key}: {$printVal}";
            return $res;
        case 'unchanged':
            $res = "{$result}" . str_repeat(
                ' ',
                \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE
            );
            $res .= "  {$key}: {$printVal}";
            return $res;
    }
}
/**
 * @param array<mixed> $tree
 */
function stylish(array $tree)
{
    return cb($tree);
}
