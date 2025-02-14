<?php

namespace Gendiff\ReadFile;

function getFixturePath(string $fileName): string
{
    $currentDir = explode('/', __DIR__);
    unset($currentDir[count($currentDir) - 1]);
    $rootDir = (implode('/', $currentDir));
    $normalizedFileName = explode('/', $fileName);
    if (count($normalizedFileName) === 1) {
        $parts = [$rootDir, 'tests', 'fixtures', $fileName];
        return realpath(implode('/', $parts));
    }
    return realpath($fileName);
}

function readFile(string $fileName): string
{
    $fullPath = getFixturePath($fileName);
    $data = file_get_contents($fullPath);
    return $data;
}
