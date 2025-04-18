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
    $nodeName = substr("{$path}{$key}", 1);
    switch ($type) {
        case 'root':
            $nodes = array_map(fn($item) => iter($item, $result, "{$path}{$key}."), $children);
            return implode("\n", $nodes);
        case 'nested':
            $nodes = array_map(fn($item) => iter($item, $result, "{$path}{$key}."), $children);
            $filteredNodes = array_filter($nodes, fn($item) => $item !== '');
            return implode("\n", $filteredNodes);
        case 'added':
            $printedValue1 = printValue($node['value1']);
            return "{$result}Property '{$nodeName}' was added with value: {$printedValue1}";
        case 'removed':
            return "{$result}Property '{$nodeName}' was removed";
        case 'updated':
            $printedValue1 = printValue($node['value1']);
            $printedValue2 = printValue($node['value2']);
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
function formatToPlain(array $tree): string
{
    return iter($tree);
}
