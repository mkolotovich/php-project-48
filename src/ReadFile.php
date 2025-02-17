<?php

namespace Gendiff\ReadFile;

function readFile(string $fileName): string
{
    if (str_contains($fileName, DIRECTORY_SEPARATOR)) {
        return file_get_contents($fileName);
    } else {
        return file_get_contents(__DIR__ . "/../tests/fixtures/{$fileName}");
    }
}
