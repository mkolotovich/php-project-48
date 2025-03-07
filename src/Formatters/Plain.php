<?php

namespace Gendiff\Formatters\Plain;

function printValue(mixed $value): mixed
{
    if (gettype($value) === 'array') {
        return '[complex value]';
    }
    if (gettype($value) === 'string') {
        if ($value === 'false' || $value === 'true' || $value === 'null') {
            return "{$value}";
        }
        return "'{$value}'";
    }
    return $value;
}
/**
 * @param array<mixed> $node
 */
function cb(array $node, string $result = '', string $path = ''): string
{
    $key = $node['key'];
    $type = $node['type'];
    $children = $node['children'];
    $printedValue = array_key_exists("value1", $node) ? printValue($node['value1']) : null;
    $printedNewValue = array_key_exists("value2", $node) ? printValue($node['value2']) : null;
    $nodeName = substr("{$path}{$key}", 1);
    switch ($type) {
        case 'root':
            $child = array_map(fn($item) => cb($item, $result, "{$path}{$key}."), $children);
            return implode("\n", $child);
        case 'nested':
            $res = array_map(fn($item) => cb($item, $result, "{$path}{$key}."), $children);
            $filtered = array_filter($res, fn($item) => $item !== '');
            return implode("\n", $filtered);
        case 'added':
            return "{$result}Property '{$nodeName}' was added with value: {$printedValue}";
        case 'removed':
            return "{$result}Property '{$nodeName}' was removed";
        case 'updated':
            return "{$result}Property '{$nodeName}' was updated. From {$printedValue} to {$printedNewValue}";
        default:
            return '';
    }
}
/**
 * @param array<mixed> $tree
 */
function plain(array $tree): string
{
    return cb($tree);
}
