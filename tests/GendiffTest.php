<?php

namespace Hexlet\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use function Gendiff\ReadFile\getFixturePath;
use function Gendiff\ReadFile\readFile;

class GendiffTest extends TestCase
{
    public function testGendiffJson(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $result = readFile('expected_stylish_file.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }
    public function testGendiffYaml(): void
    {
        $file1 = getFixturePath('file1.yaml');
        $file2 = getFixturePath('file2.yml');
        $result = readFile('expected_stylish_file.txt');
        $this->assertEquals($result, genDiff($file1, $file2));
    }
    public function testGendiffPlain(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $result = readFile('expected_plain_file.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'plain'));
    }
    public function testGendiffJsonFormatter(): void
    {
        $file1 = getFixturePath('file1.json');
        $file2 = getFixturePath('file2.json');
        $result = readFile('expected_json_file.json');
        $this->assertEquals($result, genDiff($file1, $file2, 'json'));
    }
}
