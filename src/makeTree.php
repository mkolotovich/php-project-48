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
/**
 * @param array<mixed> $children
 */
function makeNode(string $key, string $type, array $children, mixed ...$args)
{
    $node = ["key" => $key, "type" => $type, "children" => $children];
    if (count($args) === 1) {
        [$node['value']] = $args;
    } elseif (count($args) === 2) {
        [$node['value'], $node['newValue']] = $args;
    }
    if (array_key_exists('value', $node) && $node['value'] === false) {
        $node['value'] = 'false';
    }
    if (array_key_exists('value', $node) && $node['value'] === true) {
        $node['value'] = 'true';
    }
    if (array_key_exists('newValue', $node) && $node['newValue'] === null) {
        $node['newValue'] = 'null';
    }
    if (array_key_exists('value', $node) && $node['value'] === null) {
        $node['value'] = 'null';
    }
    return $node;
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
        sort($uniqueKeys);
        return makeNode($el, 'nested', makeTree($uniqueKeys, $subKeys1, $subKeys2));
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
    sort($uniqueKeys);
    $res = makeTree($uniqueKeys, $parsedData1, $parsedData2);
    $tree = makeNode('', 'root', $res);
    return $tree;
}
