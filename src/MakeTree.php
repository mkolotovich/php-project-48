<?php

namespace Gendiff\MakeTree;

function isValueObject(string $node, object $file1, object $file2): bool
{
    if (property_exists($file1, $node) && property_exists($file2, $node)) {
        if (gettype($file1->$node) === 'object' && gettype($file2->$node) === 'object') {
            return true;
        }
    }
    return false;
}
/**
 * @return array<mixed>
 */
function makeTree(object $parsedData1, object $parsedData2): array
{
    $keys1 = array_keys((array) $parsedData1);
    $keys2 = array_keys((array) $parsedData2);
    $keys = array_merge($keys1, $keys2);
    $uniqueKeys = array_unique($keys);
    $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
    return array_map(function ($key) use ($parsedData1, $parsedData2) {
        if (isValueObject($key, $parsedData1, $parsedData2)) {
            $subKeys1 = $parsedData1->$key;
            $subKeys2 = $parsedData2->$key;
            return ["key" => $key, "type" => 'nested', "children" => makeTree($subKeys1, $subKeys2)];
        }
        if (
            property_exists($parsedData1, $key) && property_exists($parsedData2, $key)
            && $parsedData1->$key === $parsedData2->$key
        ) {
            return [
                "key" => $key,
                "type" => 'unchanged',
                "children" => [],
                "value1" => $parsedData2->$key];
        }
        if (
            property_exists($parsedData1, $key) && property_exists($parsedData2, $key)
            && $parsedData1->$key !== $parsedData2->$key
        ) {
            return [
                "key" => $key,
                "type" => 'updated',
                "children" => [],
                "value1" => $parsedData1->$key,
                "value2" => $parsedData2->$key];
        }
        if (property_exists($parsedData1, $key)) {
            return ["key" => $key, "type" => 'removed', "children" => [], "value1" => $parsedData1->$key];
        }
        return ["key" => $key, "type" => 'added', "children" => [], "value1" => $parsedData2->$key];
    }, $sortedKeys);
}
