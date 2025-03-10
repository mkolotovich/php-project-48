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
 * @param array<mixed> $keys
 * @return array<mixed>
 */
function makeTree(array $keys, object $parsedData1, object $parsedData2): array
{
    return array_map(function ($el) use ($parsedData1, $parsedData2) {
        if (isValueObject($el, $parsedData1, $parsedData2)) {
            $subKeys1 = $parsedData1->$el;
            $subKeys2 = $parsedData2->$el;
            $keys1 = array_keys((array) $subKeys1);
            $keys2 = array_keys((array) $subKeys2);
            $innerKeys = array_merge($keys1, $keys2);
            $uniqueKeys = array_unique($innerKeys);
            $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
            return ["key" => $el, "type" => 'nested', "children" => makeTree($sortedKeys, $subKeys1, $subKeys2)];
        }
        if (property_exists($parsedData1, $el) && property_exists($parsedData2, $el)) {
            if ($parsedData1->$el === $parsedData2->$el) {
                return [
                    "key" => $el,
                    "type" => 'unchanged',
                    "children" => [],
                    "value1" => $parsedData2->$el];
            }
        }
        if (property_exists($parsedData1, $el) && property_exists($parsedData2, $el)) {
            if ($parsedData1->$el !== $parsedData2->$el) {
                return [
                    "key" => $el,
                    "type" => 'updated',
                    "children" => [],
                    "value1" => $parsedData1->$el,
                    "value2" => $parsedData2->$el];
            }
        }
        if (property_exists($parsedData1, $el)) {
            return ["key" => $el, "type" => 'removed', "children" => [], "value1" => $parsedData1->$el];
        }
        return ["key" => $el, "type" => 'added', "children" => [], "value1" => $parsedData2->$el];
    }, $keys);
}
/**
 * @return array<mixed>
 */
function buildTree(object $parsedData1, object $parsedData2): array
{
    $keys1 = array_keys((array) $parsedData1);
    $keys2 = array_keys((array) $parsedData2);
    $keys = array_merge($keys1, $keys2);
    $uniqueKeys = array_unique($keys);
    $sortedKeys = collect($uniqueKeys)->sort()->values()->all();
    $res = makeTree($sortedKeys, $parsedData1, $parsedData2);
    $tree = ["key" => '', "type" => 'root', "children" => $res];
    return $tree;
}
