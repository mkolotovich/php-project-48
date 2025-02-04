<?php

namespace Gendiff\Formatters\Stylish;

const SPACE = 2;
const DEPTHSTPACE = 4;

function inner($acc, $el, $replaceInner, $cb, $depth)
{
    [$key, $val] = $el;
    if (type($val) !== dict) {
        $newAcc = "{$replaceInner} * {($depth)}{$key}: {$val}\n";
    } else {
        $newAcc = "{$replaceInner} * {($depth)}{$key}: ";
        $newAcc += "{$cb($val, $replaceInner, $depth + $DEPTHSTPACE)}";
        $newAcc += "{$replaceInner} * {($depth)}' + '}\n";
    }
    return $acc + $newAcc;
}

function stringify($value, $replacer = ' ', $spaceCount = 1)
{
    if (gettype($value) !== 'array') {
        return "{$value}";
    }
    function cb($currentValue, $replaceInner, $depth)
    {
        $entries = $currentValue;
        return array_reduce($entries, $inner($replaceInner=$replaceInner, $cb=$cb, $depth=$depth, "{\n"));
    }
    $res = "{$cb($value, $replacer, $spaceCount)}";
    $res += "' ' * {($spaceCount - $DEPTHSTPACE)}}}";
    return $res;
}

function mkStr($item, $depth)
{
    if ($item["type"] == 'nested') {
        $result = "{' ' * ($DEPTHSTPACE * ($depth - 1) + $SPACE)}";
        $result += "  {$item["$key"]}: {{\n";
        return $result;
    }
    return '';
}

function stylish($tree)
{
    function cb($data, $result = '', $depth = 0)
    {
        $key = $data['key'];
        $type = $data['type'];
        $children = $data['children'];
        if (array_key_exists("value", $data)) {
            $value = $data['value'];
            $printVal = stringify($value, ' ', ($depth + 1) * \Gendiff\Formatters\Stylish\DEPTHSTPACE);
        }
        if (array_key_exists("newValue", $data)) {
            $newValue = $data['newValue'];
            $printNewVal = stringify($newValue, ' ', ($depth + 1) * \Gendiff\Formatters\Stylish\DEPTHSTPACE);
        }
        switch ($type) {
            case 'root':
                $child = array_map(fn($item) => cb($item, mkStr($item, $depth + 1), $depth + 1), $children);
                $res = "{\n{$result}" . implode("\n", $child) . "\n" ;
                $res .= str_repeat(' ', \Gendiff\Formatters\Stylish\SPACE * $depth * \Gendiff\Formatters\Stylish\SPACE) . "}";
                return $res;
            case 'nested':
                $child = array_map(fn($item) => cb($item, mkStr($item, $depth + 1), $depth + 1), $children);
                $res = "{$result}{os.linesep.join($child)}\n";
                $res += "{' ' * ($SPACE * $depth * $SPACE)}}}";
                return $res;
            case 'updated':
                $res = "{$result}";
                $res .= str_repeat(' ', \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE)."- {$key}";
                $res .= ": {$printVal}\n";
                $res .= str_repeat(' ', \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE)."+ {$key}";
                $res .= ": {$printNewVal}";
                return $res;
            case 'added':
                $res = "{$result}".str_repeat(' ', (\Gendiff\Formatters\Stylish\DEPTHSTPACE* ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE));
                $res .= "+ {$key}: {$printVal}";
                return $res;
            case 'removed':
                $res = "{$result}" . str_repeat(' ', \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE);
                $res .= "- {$key}: {$printVal}";
                return $res;
            case 'unchanged':
                $res = "{$result}" . str_repeat(' ', \Gendiff\Formatters\Stylish\DEPTHSTPACE * ($depth - 1) + \Gendiff\Formatters\Stylish\SPACE);
                $res .= "  {$key}: {$printVal}";
                return $res;
        }
    }
    return cb($tree);
}
