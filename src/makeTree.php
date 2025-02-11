<?php

namespace Gendiff\MakeTree;

/**
 * @param array<mixed> $file1
 * @param array<mixed> $file2
 */
function isValueObject(string $node, array $file1, array $file2)
{
    if (array_key_exists($node, $file1) && array_key_exists($node, $file2)) {
        if (gettype($file1[$node]) === 'array' && gettype($file2[$node]) === 'array') {
            return true;
        }
    }
    return false;
}
function normalizeValue(mixed $value)
{
    if ($value  === false) {
        return 'false';
    } elseif ($value === true) {
        return 'true';
    } elseif ($value === null) {
        return 'null';
    } else {
        return $value;
    }
}
/**
 * @param array<mixed> $children
 */
function makeNode(string $key, string $type, array $children, mixed $value = null, mixed $newValue = null)
{
    if ($type !== 'nested' && $type !== 'root') {
        return [
            "key" => $key,
            "type" => $type,
            "children" => $children,
            "value" => normalizeValue($value),
            "newValue" => normalizeValue($newValue)
        ];
    }
    return [
        "key" => $key,
        "type" => $type,
        "children" => $children,
    ];
}
/**
 * @param array<mixed> $parsedData1
 * @param array<mixed> $parsedData2
 */
function buildNode(string $el, array $parsedData1, array $parsedData2)
{
    if (isValueObject($el, $parsedData1, $parsedData2)) {
        $subKeys1 = $parsedData1[$el];
        $subKeys2 = $parsedData2[$el];
        $keys1 = array_keys($subKeys1);
        $keys2 = array_keys($subKeys2);
        $innerKeys = array_merge($keys1, $keys2);
        $uniqueKeys = array_unique($innerKeys);
        $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
        return makeNode($el, 'nested', makeTree($sortedKeys, $subKeys1, $subKeys2));
    }
    if (array_key_exists($el, $parsedData1) && array_key_exists($el, $parsedData2)) {
        if ($parsedData1[$el] === $parsedData2[$el]) {
            return makeNode($el, 'unchanged', [], $parsedData2[$el]);
        }
    }
    if (array_key_exists($el, $parsedData1) && array_key_exists($el, $parsedData2)) {
        if ($parsedData1[$el] !== $parsedData2[$el]) {
            return makeNode($el, 'updated', [], $parsedData1[$el], $parsedData2[$el]);
        }
    }
    if (array_key_exists($el, $parsedData1)) {
        return makeNode($el, 'removed', [], $parsedData1[$el]);
    }
    return makeNode($el, 'added', [], $value = $parsedData2[$el]);
}
/**
 * @param array<mixed> $keys
 * @param array<mixed> $parsedData1
 * @param array<mixed> $parsedData2
 */
function makeTree(array $keys, array $parsedData1, array $parsedData2)
{
    return array_map(fn($item) => buildNode($item, $parsedData1, $parsedData2), $keys);
}
/**
 * @param array<mixed> $parsedData1
 * @param array<mixed> $parsedData2
 */
function buildTree(array $parsedData1, array $parsedData2)
{
    $keys1 = array_keys($parsedData1);
    $keys2 = array_keys($parsedData2);
    $keys = array_merge($keys1, $keys2);
    $uniqueKeys = array_unique($keys);
    $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
    $res = makeTree($sortedKeys, $parsedData1, $parsedData2);
    $tree = makeNode('', 'root', $res);
    return $tree;
}
