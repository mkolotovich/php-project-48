<?php

namespace Gendiff\Formatters\Stylish;

const SPACE = 2;
const DEPTHSTPACE = 4;

function stringifyIter(object $node, string $replaceInner, int $depth): string
{
    $entries = array_keys((array) $node);
    return array_reduce($entries, function ($acc, $key) use ($replaceInner, $depth, $node) {
        $val = $node->$key;
        if (gettype($val) !== 'object') {
            $indent = str_repeat($replaceInner, $depth);
            $newAcc =  "{$indent}{$key}: {$val}\n";
        } else {
            $beginIndent = str_repeat($replaceInner, $depth);
            $value = stringifyIter($val, $replaceInner, $depth + DEPTHSTPACE);
            $endIndent = str_repeat($replaceInner, $depth);
            $newAcc = "{$beginIndent}{$key}: {$value}{$endIndent}}\n";
        }
        return "{$acc}{$newAcc}";
    }, "{\n");
}

function stringify(mixed $value, string $replacer = ' ', int $spaceCount = 1): mixed
{
    if (gettype($value) === 'boolean') {
        if ($value === false) {
            return 'false';
        } else {
            return 'true';
        }
    }
    if (gettype($value) === 'NULL') {
        return 'null';
    }
    if (gettype($value) !== 'object') {
        return "{$value}";
    }
    $keyValue = stringifyIter($value, $replacer, $spaceCount);
    $indent = str_repeat(' ', $spaceCount - DEPTHSTPACE);
    return "{$keyValue}{$indent}}";
}
function makeIndent(int $depth): int
{
    return DEPTHSTPACE * ($depth - 1) + SPACE;
}
/**
 * @param array<mixed> $item
 */
function makeAccum(array $item, int $depth): string
{
    if ($item["type"] === 'nested') {
        $indent = str_repeat(' ', makeIndent($depth));
        return "{$indent}  {$item["key"]}: {\n";
    }
    return '';
}
/**
 * @param array<mixed> $data
 */
function stylishIter(array $data, string $result = '', int $depth = 0): string
{
    $key = $data['key'];
    $type = $data['type'];
    $children = $data['children'];
    $value1 = array_key_exists("value1", $data) ? stringify($data['value1'], ' ', ($depth + 1) * DEPTHSTPACE) : null;
    $value2 = array_key_exists("value2", $data) ? stringify($data['value2'], ' ', ($depth + 1) * DEPTHSTPACE) : null;
    switch ($type) {
        case 'root':
            $res = array_map(fn($item) => stylishIter($item, makeAccum($item, $depth + 1), $depth + 1), $children);
            $resToStr = implode("\n", $res);
            $indent = str_repeat(' ', SPACE * $depth * SPACE);
            return "{\n{$result}{$resToStr}\n{$indent}}";
        case 'nested':
            $res = array_map(fn($item) => stylishIter($item, makeAccum($item, $depth + 1), $depth + 1), $children);
            $resToStr = implode("\n", $res);
            $indent = str_repeat(' ', SPACE * $depth * SPACE);
            return "{$result}{$resToStr}\n{$indent}}";
        case 'updated':
            $beginIndent = str_repeat(' ', makeIndent($depth));
            $endIndent = str_repeat(' ', makeIndent($depth));
            return "{$result}{$beginIndent}- {$key}: {$value1}\n{$endIndent}+ {$key}: {$value2}";
        case 'added':
            $indent = str_repeat(' ', makeIndent($depth));
            return "{$result}{$indent}+ {$key}: {$value1}";
        case 'removed':
            $indent = str_repeat(' ', makeIndent($depth));
            return "{$result}{$indent}- {$key}: {$value1}";
        default:
            $indent = str_repeat(' ', makeIndent($depth));
            return "{$result}{$indent}  {$key}: {$value1}";
    }
}
/**
 * @param array<mixed> $tree
 */
function stylish(array $tree): string
{
    return stylishIter($tree);
}
