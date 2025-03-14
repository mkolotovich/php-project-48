<?php

namespace Gendiff\ReadFile;

function readFile(string $fileName): string
{
    return file_get_contents($fileName);
}
