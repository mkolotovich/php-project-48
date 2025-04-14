<?php

namespace Gendiff\Formatters\Plain;

function printValue(mixed $value): mixed
{
    if (gettype($value) === 'object') {
        return '[complex value]';
    } if ($value === false) {
        return 'false';
    } if ($value === true) {
        return 'true';
    } if ($value === null) {
        return 'null';
    } if (gettype($value) === 'string') {
        return "'{$value}'";
    }
    return "{$value}";
}
/**
 * @param array<mixed> $node
 */
function iter(array $node, string $result = '', string $path = ''): string
{
    $key = $node['key'];
    $type = $node['type'];
    $children = $node['children'];
    $printedValue1 = $type === 'added' || $type === 'updated' ? printValue($node['value1']) : null;
    $printedValue2 = $type === 'updated' ? printValue($node['value2']) : null;
    $nodeName = substr("{$path}{$key}", 1);
    switch ($type) {
        case 'root':
            $res = array_map(fn($item) => iter($item, $result, "{$path}{$key}."), $children);
            return implode("\n", $res);
        case 'nested':
            $res = array_map(fn($item) => iter($item, $result, "{$path}{$key}."), $children);
            $filtered = array_filter($res, fn($item) => $item !== '');
            return implode("\n", $filtered);
        case 'added':
            return "{$result}Property '{$nodeName}' was added with value: {$printedValue1}";
        case 'removed':
            return "{$result}Property '{$nodeName}' was removed";
        case 'updated':
            return "{$result}Property '{$nodeName}' was updated. From {$printedValue1} to {$printedValue2}";
        case 'unchanged':
            return '';
        default:
            throw new \Exception("Incorrect node type!");
    }
}
/**
 * @param array<mixed> $tree
 */
function plain(array $tree): string
{
    return iter($tree);
}
