<?php

namespace Gendiff\MakeTree;

/**
 * @param array<mixed> $file1
 * @param array<mixed> $file2
 */
function isValueObject(string $node, array $file1, array $file2): bool
{
    if (array_key_exists($node, $file1) && array_key_exists($node, $file2)) {
        if (gettype($file1[$node]) === 'array' && gettype($file2[$node]) === 'array') {
            return true;
        }
    }
    return false;
}
function normalizeValue(mixed $value): mixed
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
 * @param array<mixed> $keys
 * @param array<mixed> $parsedData1
 * @param array<mixed> $parsedData2
 * @return array<mixed>
 */
function makeTree(array $keys, array $parsedData1, array $parsedData2): array
{
    return array_map(function ($el) use ($parsedData1, $parsedData2) {
        if (isValueObject($el, $parsedData1, $parsedData2)) {
            $subKeys1 = $parsedData1[$el];
            $subKeys2 = $parsedData2[$el];
            $keys1 = array_keys($subKeys1);
            $keys2 = array_keys($subKeys2);
            $innerKeys = array_merge($keys1, $keys2);
            $uniqueKeys = array_unique($innerKeys);
            $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
            return ["key" => $el, "type" => 'nested', "children" => makeTree($sortedKeys, $subKeys1, $subKeys2)];
        }
        if (array_key_exists($el, $parsedData1) && array_key_exists($el, $parsedData2)) {
            if ($parsedData1[$el] === $parsedData2[$el]) {
                return [
                    "key" => $el,
                    "type" => 'unchanged',
                    "children" => [],
                    "value" => normalizeValue($parsedData2[$el])];
            }
        }
        if (array_key_exists($el, $parsedData1) && array_key_exists($el, $parsedData2)) {
            if ($parsedData1[$el] !== $parsedData2[$el]) {
                return [
                    "key" => $el,
                    "type" => 'updated',
                    "children" => [],
                    "value" => normalizeValue($parsedData1[$el]),
                    "newValue" => normalizeValue($parsedData2[$el])];
            }
        }
        if (array_key_exists($el, $parsedData1)) {
            return ["key" => $el, "type" => 'removed', "children" => [], "value" => normalizeValue($parsedData1[$el])];
        }
        return ["key" => $el, "type" => 'added', "children" => [], "value" => normalizeValue($parsedData2[$el])];
    }, $keys);
}
/**
 * @param array<mixed> $parsedData1
 * @param array<mixed> $parsedData2
 * @return array<mixed>
 */
function buildTree(array $parsedData1, array $parsedData2): array
{
    $keys1 = array_keys($parsedData1);
    $keys2 = array_keys($parsedData2);
    $keys = array_merge($keys1, $keys2);
    $uniqueKeys = array_unique($keys);
    $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
    $res = makeTree($sortedKeys, $parsedData1, $parsedData2);
    $tree = ["key" => '', "type" => 'root', "children" => $res];
    return $tree;
}
