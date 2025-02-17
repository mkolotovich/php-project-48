<?php

namespace Hexlet\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    private function getFixturePath(string $fileName): string
    {
        $parts = [__DIR__, 'fixtures', $fileName];
        return realpath(implode('/', $parts));
    }
    public function testGendiffJson(): void
    {
        $file1 = $this->getFixturePath('file1.json');
        $file2 = $this->getFixturePath('file2.json');
        $result = file_get_contents($this->getFixturePath('expected_stylish_file.txt'));
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }
    public function testGendiffYaml(): void
    {
        $file1 = $this->getFixturePath('file1.yaml');
        $file2 = $this->getFixturePath('file2.yml');
        $result = file_get_contents($this->getFixturePath('expected_stylish_file.txt'));
        $this->assertEquals($result, genDiff($file1, $file2));
    }
    public function testGendiffPlain(): void
    {
        $file1 = $this->getFixturePath('file1.json');
        $file2 = $this->getFixturePath('file2.json');
        $result = file_get_contents($this->getFixturePath('expected_plain_file.txt'));
        $this->assertEquals($result, genDiff($file1, $file2, 'plain'));
    }
    public function testGendiffJsonFormatter(): void
    {
        $file1 = $this->getFixturePath('file1.json');
        $file2 = $this->getFixturePath('file2.json');
        $result = file_get_contents($this->getFixturePath('expected_json_file.json'));
        $this->assertEquals($result, genDiff($file1, $file2, 'json'));
    }
}
