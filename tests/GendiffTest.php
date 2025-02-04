<?php

namespace Hexlet\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Gendiff\generateDiff;
use function Gendiff\ReadFile\getFixturePath;

class GendiffTest extends TestCase
{
    public function testGendiffJson(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $result = file_get_contents(getFixturePath('expected_plain_file.txt'));
        $this->assertEquals($result, generateDiff($file1, $file2));
    }
}
