<?php

namespace Gendiff\Formatters\Plain;

function printValue($value)
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

function plain($tree)
{
    function cb($node, $result = '', $path = '')
    {
        $key = $node['key'];
        $type = $node['type'];
        $children = $node['children'];
        if (array_key_exists("value", $node)) {
            $value = $node['value'];
            $printedValue = printValue($value);
        }
        if (array_key_exists("newValue", $node)) {
            $newValue = $node['newValue'];
            $printedNewValue = printValue($newValue);
        }
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
                $res = "{$result}Property '{$nodeName}' was added with value: ";
                $res .= "{$printedValue}";
                return $res;
            case 'removed':
                return "{$result}Property '{$nodeName}' was removed";
            case 'updated':
                $res = "{$result}Property '{$nodeName}' was updated. From ";
                $res .= "{$printedValue} to {$printedNewValue}";
                return $res;
            case 'unchanged':
                return '';
        }
    }
    return cb($tree);
}
